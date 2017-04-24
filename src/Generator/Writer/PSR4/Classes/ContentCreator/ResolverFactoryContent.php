<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\ResolverFactory;

class ResolverFactoryContent extends BaseContentCreator {

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
		// TODO: Implement getVariables() method.
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		// TODO: Implement getNamespace() method.
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		// TODO: Implement getClassName() method.
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		// TODO: Implement getParentClassName() method.
	}

	/**
	 * @return ResolverFactory
	 */
	public function getResolverFactoryClass() {

	}

	/**
	 * @param ObjectType $type
	 */
	public function getResolveFunctionsForType($type) {
		// Check if $type is a resolvable fragment
		// Add resolver factory
	}

	/**
	 * @param ResolverFactory $_resolverFactory
	 */
	public function setResolverFactoryClass($_resolverFactory) {
	}
}