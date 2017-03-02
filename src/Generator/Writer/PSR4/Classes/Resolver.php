<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;

class Resolver extends SingleClass {

	/**
	 * @var Type
	 */
	protected $_associatedType;


	public function getContent() {
		throw new Exception("ToDo: Implement");
	}

	/**
	 * @return Type
	 */
	public function getAssociatedType() {
		return $this->_associatedType;
	}

	/**
	 * @param Type $associatedType
	 */
	public function setAssociatedType(Type $associatedType) {
		$this->_associatedType = $associatedType;
	}
}