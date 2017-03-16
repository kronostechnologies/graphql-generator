<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;

class Resolver extends SingleClass {

	const STUB_FILE = 'resolver.stub';

	/**
	 * @var BaseTypeGenerator
	 */
	protected $_generatorType;

	/**
	 * @return ResolverContent
	 */
	public function getContentCreator() {
		$resolverContent = new ResolverContent();
		$resolverContent->setResolverClass($this);
		$resolverContent->setTypeGenerator($this->getGeneratorType());

		return $resolverContent;
	}

	/**
	 * @return BaseTypeGenerator
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGenerator $generatorType
	 */
	public function setGeneratorType($generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
	}
}