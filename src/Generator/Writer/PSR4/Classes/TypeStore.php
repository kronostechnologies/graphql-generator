<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\TypeStoreContent;

class TypeStore extends SingleClass {
	const STUB_FILE = 'typestore.stub';

	/**
	 * @var ObjectType[]
	 */
	protected $_typesToImplement;

	/**
	 * @param ObjectType $type
	 */
	public function addTypeImplementation(ObjectType $type) {
		$this->_typesToImplement[] = $type;
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

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
	}
}