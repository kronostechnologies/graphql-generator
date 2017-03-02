<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;

class ObjectType extends SingleClass {

	/**
	 * @var Resolver
	 */
	protected $_associatedResolver;

	public function getContent() {
		throw new Exception("ToDo: Implement");
	}

	/**
	 * @return Resolver
	 */
	public function getAssociatedResolver() {
		return $this->_associatedResolver;
	}

	/**
	 * @param Resolver $associatedResolver
	 */
	public function setAssociatedResolver(Resolver $associatedResolver) {
		$this->_associatedResolver = $associatedResolver;
	}
}