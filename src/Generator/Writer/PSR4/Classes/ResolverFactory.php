<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverFactoryContent;

class ResolverFactory extends SingleClass {
	const STUB_FILE = 'resolver-factory.stub';

	/**
	 * @return ResolverFactoryContent
	 */
	public function getContentCreator() {

	}

	/**
	 * @return string
	 */
	public function getStubFileName() {

	}

	/**
	 * @type ObjectType[]
	 */
	public function getTypeResolversToAdd() {

	}

	/**
	 * @param ObjectType $type
	 */
	public function addResolveableTypeImplementation($type) {

	}
}