<?php


namespace GraphQLGen\Generator\Writer\Namespaced\Classes;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\DTOContent;

class DTO extends SingleClass {
	const STUB_FILE = 'dto.stub';

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_generatorType;

	/**
	 * @var bool
	 */
	protected $_renderContent = true;

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

	public function enableContent() {
		$this->_renderContent = true;
	}

	public function disableContent() {
		$this->_renderContent = false;
	}

	/**
	 * @return bool
	 */
	public function shouldRenderContent() {
		return $this->_renderContent;
	}

}