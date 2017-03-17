<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\DTOContent;

class DTO extends SingleClass {
	const STUB_FILE = 'dto.stub';

	/**
	 * @var BaseTypeGenerator
	 */
	protected $_generatorType;

	/**
	 * @return BaseContentCreator
	 */
	public function getContentCreator() {
		$dtoContent = new DTOContent();
		$dtoContent->setDTOClass($this);
		$dtoContent->setTypeGenerator($this->getGeneratorType());

		return $dtoContent;
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
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

}