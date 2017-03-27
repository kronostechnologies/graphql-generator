<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;

class Resolver extends SingleClass {

	const STUB_FILE = 'resolver.stub';

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_generatorType;

	/**
	 * @return ResolverContent
	 */
	public function getContentCreator() {
		$resolverContent = new ResolverContent();
		$resolverContent->setResolverClass($this);
		$resolverContent->setFragmentGenerator($this->getGeneratorType());

		return $resolverContent;
	}

	/**
	 * @return FragmentGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param FragmentGeneratorInterface $generatorType
	 */
	public function setFragmentGenerator($generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
	}
}