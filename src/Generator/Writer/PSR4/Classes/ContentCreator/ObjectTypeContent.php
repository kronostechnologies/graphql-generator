<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQL\Type\Definition\ScalarType;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;

class ObjectTypeContent extends BaseContentCreator {
	/**
	 * @var ObjectType
	 */
	protected $_objectTypeClass;

	/**
	 * @var BaseTypeGeneratorInterface
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
		if (in_array(get_class($this->getGeneratorType()), [InterfaceDeclaration::class, Type::class])) {
			$contentAsLines[] = " \$this->resolver = new {$resolverName}();";
		}

		if (get_class($this->getGeneratorType()) == Scalar::class) {
			$contentAsLines[] = 'parent::__construct();';
			$contentAsLines[] = $this->getGeneratorType()->generateTypeDefinition();
		} else {
			$contentAsLines[] = "parent::__construct(";
			$contentAsLines[] = $this->getGeneratorType()->generateTypeDefinition();
			$contentAsLines[] = ");";
		}

		$contentAsLines[] = "}";

		return implode("\n", $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$variableDeclarationsAsLines = [];

		if (in_array(get_class($this->getGeneratorType()), [InterfaceDeclaration::class, Type::class])) {
			$variableDeclarationsAsLines[] = "public \$resolver;";
		}
		$variableDeclarationsAsLines[] = $this->getGeneratorType()->getVariablesDeclarations();

		return implode("\n", $variableDeclarationsAsLines);
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
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
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