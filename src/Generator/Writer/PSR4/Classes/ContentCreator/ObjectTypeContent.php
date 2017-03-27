<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQL\Type\Definition\ScalarType;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Types\Input;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Types\Union;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;

class ObjectTypeContent extends BaseContentCreator {
	/**
	 * @var ObjectType
	 */
	protected $_objectTypeClass;

	/**
	 * @var FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	protected $_generatorType;

	/**
	 * @return ObjectType
	 */
	public function getObjectTypeClass() {
		return $this->_objectTypeClass;
	}

	/**
	 * @param ObjectType $objectTypeClass
	 */
	public function setObjectTypeClass($objectTypeClass) {
		$this->_objectTypeClass = $objectTypeClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$contentAsLines = [];
		$resolverName = $this->getGeneratorType()->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX;

		$contentAsLines[] = "public function __construct() {";
		if (in_array(get_class($this->getGeneratorType()), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class, UnionFragmentGenerator::class])) {
			$contentAsLines[] = " \$this->resolver = new {$resolverName}();";
		}

		if (get_class($this->getGeneratorType()) == ScalarFragmentGenerator::class) {
			$contentAsLines[] = 'parent::__construct();';
			$contentAsLines[] = $this->getGeneratorType()->generateTypeDefinition();
		} else {
			$contentAsLines[] = "parent::__construct(";
			$contentAsLines[] = $this->getGeneratorType()->generateTypeDefinition();
			$contentAsLines[] = ");";
		}

		$contentAsLines[] = "}";

		return implode(PHP_EOL, $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$variableDeclarationsAsLines = [];

		if (in_array(get_class($this->getGeneratorType()), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class, UnionFragmentGenerator::class])) {
			$variableDeclarationsAsLines[] = "public \$resolver;";
		}

		if ($this->getGeneratorType() instanceof VariablesDefiningGeneratorInterface) {
			$variableDeclarationsAsLines[] = $this->getGeneratorType()->getVariablesDeclarations();
		}


		return implode(PHP_EOL, $variableDeclarationsAsLines);
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getObjectTypeClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getObjectTypeClass()->getClassName();
	}

	/**
	 * @return FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param FragmentGeneratorInterface|VariablesDefiningGeneratorInterface $generatorType
	 */
	public function setGeneratorType($generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return $this->getObjectTypeClass()->getParentClassName();
	}
}