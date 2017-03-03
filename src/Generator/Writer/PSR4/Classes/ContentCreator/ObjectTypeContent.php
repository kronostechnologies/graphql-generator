<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;

class ObjectTypeContent extends BaseContentCreator {
	/**
	 * @var ObjectType
	 */
	protected $_objectTypeClass;

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
		return $this->getObjectTypeClass()->getGeneratorType()->generateTypeDefinition();
	}

	public function getVariables() {
		// TODO: Implement getVariables() method.
	}

	public function getNamespace() {
		// TODO: Implement getNamespace() method.
	}

	public function getClassName() {
		// TODO: Implement getClassName() method.
	}
}