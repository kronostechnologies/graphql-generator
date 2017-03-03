<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContent extends BaseContentCreator {
	/**
	 * @var Resolver
	 */
	protected $_resolverClass;

	/**
	 * @return Resolver
	 */
	public function getResolverClass() {
		return $this->_resolverClass;
	}

	/**
	 * @param Resolver $resolverClass
	 */
	public function setResolverClass($resolverClass) {
		$this->_resolverClass = $resolverClass;
	}

	public function getContent() {

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