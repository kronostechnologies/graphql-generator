<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\ResolverFactory;

class ResolverFactoryContent extends BaseContentCreator {
	/**
	 * @var ResolverFactory
	 */
	protected $_resolverFactoryClass;

	/**
	 * @return string
	 */
	public function getContent() {
		// TODO: Implement getContent() method.
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		return '';
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getResolverFactoryClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getResolverFactoryClass()->getClassName();
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return '';
	}

	/**
	 * @return ResolverFactory
	 */
	public function getResolverFactoryClass() {
		return $this->_resolverFactoryClass;
	}

	/**
	 * @param ObjectType $type
	 */
	public function getResolveFunctionsForType($type) {
		// Check if $type is a resolvable fragment
		// Add resolver factory
	}

	/**
	 * @param ResolverFactory $resolverFactory
	 */
	public function setResolverFactoryClass($resolverFactory) {
		$this->_resolverFactoryClass = $resolverFactory;
	}
}