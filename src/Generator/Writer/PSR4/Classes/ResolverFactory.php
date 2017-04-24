<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverFactoryContent;

class ResolverFactory extends SingleClass {
	const STUB_FILE = 'resolver-factory.stub';

	/**
	 * @var FragmentGeneratorInterface[]
	 */
	protected $_typeResolversToAdd = [];

	/**
	 * @return ResolverFactoryContent
	 */
	public function getContentCreator() {
		$resolverFactoryContent = new ResolverFactoryContent();
		$resolverFactoryContent->setResolverFactoryClass($this);

		return $resolverFactoryContent;
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
	}

	/**
	 * @return FragmentGeneratorInterface[]
	 */
	public function getTypeResolversToAdd() {
		return $this->_typeResolversToAdd;
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function addResolveableTypeImplementation($fragmentGenerator) {
		$this->_typeResolversToAdd[] = $fragmentGenerator;
	}
}