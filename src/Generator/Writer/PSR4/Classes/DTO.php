<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\DTOContent;

class DTO extends SingleClass {
	const STUB_FILE = 'dto.stub';

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_generatorType;

	/**
	 * @return BaseContentCreator
	 */
	public function getContentCreator() {
		$dtoContent = new DTOContent();
		$dtoContent->setDTOClass($this);
		$dtoContent->setFragmentGenerator($this->getGeneratorType());

		return $dtoContent;
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		return self::STUB_FILE;
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

}