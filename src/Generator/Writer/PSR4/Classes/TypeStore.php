<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\TypeStoreContent;

class TypeStore extends SingleClass {
	/**
	 * @var ObjectType[]
	 */
	protected $_typesToImplement;

	public function getContent() {
		throw new Exception("ToDo: Implement");
	}

	/**
	 * @param ObjectType $type
	 */
	public function addTypeImplementation(ObjectType $type) {
		$this->_typesToImplement = $type;
	}

	/**
	 * @return ObjectType[]
	 */
	public function getTypesToImplement() {
		return $this->_typesToImplement;
	}

	/**
	 * @return TypeStoreContent
	 */
	public function getContentCreator() {
		$typeStoreContent = new TypeStoreContent();
		$typeStoreContent->setTypeStoreClass($this);

		return $typeStoreContent;
	}
}