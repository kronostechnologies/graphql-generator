<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContent extends BaseContentCreator {
	/**
	 * @var Resolver
	 */
	protected $_resolverClass;

	/**
	 * @var BaseTypeGenerator
	 */
	protected $_typeGenerator;

	/**
	 * @return Resolver
	 */
	public function getResolverClass() {
		return $this->_resolverClass;
	}

	/**
	 * @param Resolver $resolverClass
	 */
	public function setResolverClass($resolverClass) {
		$this->_resolverClass = $resolverClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$typeGeneratorClass = $this->getTypeGeneratorClass();
		$contentAsLines = [];

		if(in_array($typeGeneratorClass, [InterfaceDeclaration::class, Type::class])) {
			/** @var InterfaceDeclaration|Type $typeGenerator */
			$typeGenerator = $this->getTypeGenerator();

			foreach($typeGenerator->getFields() as $field) {
				if ($field->getFieldType()->isPrimaryType()) {
					continue;
				}

			    $fieldNameFirstLetterCapped = ucwords($field->getName());
				$contentAsLines[] = "function resolve{$fieldNameFirstLetterCapped}(\$root, \$args) { /** ToDo: Implement */ }";
			}
		}

		return implode("\n", $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		return "";
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getResolverClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getResolverClass()->getClassName();
	}

	/**
	 * @return BaseTypeGenerator
	 */
	public function getTypeGenerator() {
		return $this->_typeGenerator;
	}

	/**
	 * @param BaseTypeGenerator $typeGenerator
	 */
	public function setTypeGenerator(BaseTypeGenerator $typeGenerator) {
		$this->_typeGenerator = $typeGenerator;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getTypeGeneratorClass() {
		if ($this->getTypeGenerator() === null) {
			throw new Exception("Internal Exception: Type generator is not defined for {$this->getResolverClass()->getClassName()}");
		}

		return get_class($this->_typeGenerator);
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}
}