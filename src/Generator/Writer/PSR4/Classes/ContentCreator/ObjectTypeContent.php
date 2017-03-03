<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
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
		$resolverName = $this->getGeneratorType()->getName() . ClassComposer::TYPE_STORE_CLASS_NAME;

		$contentAsLines[] = "public function __construct() { \$this->resolver = new {$resolverName}(); }";
		$contentAsLines[] = "public static function getTypeDefinition() {";
		$contentAsLines[] = $this->getGeneratorType()->generateTypeDefinition();
		$contentAsLines[] = "}";

		return implode("\n", $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$variableDeclarationsAsLines = [];

		$variableDeclarationsAsLines[] = "public \$resolver;";
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
}