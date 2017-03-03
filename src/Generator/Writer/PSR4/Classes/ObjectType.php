<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;

class ObjectType extends SingleClass {
	/**
	 * @var BaseTypeGeneratorInterface
	 */
	protected $_generatorType;

	/**
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
	 */
	public function setGeneratorType(BaseTypeGeneratorInterface $generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return ObjectTypeContent
	 */
	public function getContentCreator() {
		$objectTypeContent = new ObjectTypeContent();
		$objectTypeContent->setObjectTypeClass($this);
		$objectTypeContent->setGeneratorType($this->getGeneratorType());

		return $objectTypeContent;
	}
}