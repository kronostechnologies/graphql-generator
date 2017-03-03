<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;

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

	/**
	 * @return ResolverContent
	 */
	public function getContentCreator() {
		$resolverContent = new ResolverContent();
		$resolverContent->setResolverClass($this);

		return $resolverContent;
	}
}