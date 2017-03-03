<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;

class Resolver extends SingleClass {

	/**
	 * @var BaseTypeGeneratorInterface
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
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
	 */
	public function setGeneratorType($generatorType) {
		$this->_generatorType = $generatorType;
	}
}