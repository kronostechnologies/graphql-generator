<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;

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
}