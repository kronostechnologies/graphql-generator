<?php


namespace GraphQLGen\Generator\Writer;


class StubFile {
	const DEPENDENCIES_DECLARATION = "// @generate:Dependencies";
	const CONTENT_DECLARATION = "// @generate:Content";

	/**
	 * @var string
	 */
	protected $_content;

	/**
	 * @var string[]
	 */
	protected $_contentAsLines;

	/**
	 * @param string $searchText
	 * @return string|null
	 */
	public function getLineWithText($searchText) {
		$lines = $this->getLinesWithText($searchText);

		return !empty($lines) ? array_shift($lines) : null;
	}

	/**
	 * @param $searchText
	 * @return string[]
	 */
	public function getLinesWithText($searchText) {
		return array_values(
			array_filter($this->_contentAsLines, function ($line) use ($searchText) {
				return strpos($line, $searchText) !== false;
			})
		);
	}

	/**
	 * @return null|string
	 */
	public function getDependenciesDeclarationLine() {
		return $this->getLineWithText(self::DEPENDENCIES_DECLARATION);
	}

	/**
	 * @return null|string
	 */
	public function getContentDeclarationLine() {
		return $this->getLineWithText(self::CONTENT_DECLARATION);
	}

	/**
	 * @param string $original
	 * @param string $new
	 */
	public function replaceTextInStub($original, $new) {
		$this->_content = str_replace($original, $new, $this->_content);

		$this->splitContentAsLines();
	}

	/**
	 * @return string
	 */
	public function getFileContent() {
		return $this->_content;
	}

	/**
	 * @return string[]
	 */
	public function getContentAsLines() {
		return $this->_contentAsLines;
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeContent($formattedContent) {
		$this->replaceTextInStub(StubFile::CONTENT_DECLARATION, $formattedContent);
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeDependenciesDeclaration($formattedContent) {
		$this->replaceTextInStub(StubFile::DEPENDENCIES_DECLARATION, $formattedContent);
	}

	public function setFileContent($content) {
		$this->_content = $content;

		$this->splitContentAsLines();
	}

	private function splitContentAsLines() {
		$this->_contentAsLines = explode("\n", $this->_content);
	}
}