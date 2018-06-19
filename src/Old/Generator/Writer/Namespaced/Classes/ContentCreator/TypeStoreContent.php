<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator;


use GraphQLGen\Old\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\TypeStore;

class TypeStoreContent extends BaseContentCreator {
	const RESOLVER_FACTORY_VAR = '$ResolverFactory';
	const RESOLVER_FACTORY_SETTER_FUNC = 'public static function setResolverFactory($resolverFactory) { self::' . self::RESOLVER_FACTORY_VAR . ' = $resolverFactory; }';
	const RESOLVER_FACTORY_GETTER_FUNC = 'public static function getResolverFactory() { return self::' . self::RESOLVER_FACTORY_VAR . '; }';

	/**
	 * @var TypeStore
	 */
	protected $_typeStoreClass;

	/**
	 * @return TypeStore
	 */
	public function getTypeStoreClass() {
		return $this->_typeStoreClass;
	}

	/**
	 * @param TypeStore $typeStoreClass
	 */
	public function setTypeStoreClass(TypeStore $typeStoreClass) {
		$this->_typeStoreClass = $typeStoreClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$lineSeparatedContent = [self::RESOLVER_FACTORY_GETTER_FUNC, self::RESOLVER_FACTORY_SETTER_FUNC];

		foreach($this->getTypeStoreClass()->getTypesToImplement() as $typeToImplement) {
			$lineSeparatedContent[] = $this->getFunctionForType($typeToImplement);
		}

		return implode(PHP_EOL, $lineSeparatedContent);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$lineSeparatedVariables = [$this->getResolverFactoryVar()];

		foreach($this->getTypeStoreClass()->getTypesToImplement() as $typeToImplement) {
			$lineSeparatedVariables[] = $this->getVariableForType($typeToImplement);
		}

		return implode(PHP_EOL, $lineSeparatedVariables);
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getTypeStoreClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getTypeStoreClass()->getClassName();
	}

	/**
	 * @return string
	 */
	protected function getResolverFactoryVar() {
		return "private static " . self::RESOLVER_FACTORY_VAR . ";";
	}

	/**
	 * @param ObjectType $type
	 * @return string
	 */
	protected function getFunctionForType(ObjectType $type) {
		$requiresResolverFactory = in_array(get_class($type->getFragmentGenerator()), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class, UnionFragmentGenerator::class, ScalarFragmentGenerator::class]);
		$constructorFragment = $requiresResolverFactory ? "self::" . self::RESOLVER_FACTORY_VAR : "";

		return "public static function {$type->getFragmentGenerator()->getName()}() { return self::\${$type->getFragmentGenerator()->getName()} ?: (self::\${$type->getFragmentGenerator()->getName()} = new {$type->getClassName()}({$constructorFragment})); }";
	}

	/**
	 * @param ObjectType $type
	 * @return string
	 */
	protected function getVariableForType(ObjectType $type) {
		return "private static \${$type->getFragmentGenerator()->getName()};";
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}
}