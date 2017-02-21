<?php


namespace GraphQLGen\Generator\Writer;


class StubFile {
	const USES_DECLARATION = "'UsesDeclarations';";
	const TYPE_DEFINITION_DECLARATION = "'TypeDefinitionDeclaration';";

	/**
	 * @var string
	 */
	protected $_content;

	/**
	 * @var string[]
	 */
	protected $_contentAsLines;

	/**
	 * @param string $content
	 */
	public function __construct($content) {
		$this->_content = $content;

		$this->splitContentAsLines();
	}

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
		return array_filter($this->_contentAsLines, function($line) use ($searchText) {
			return strpos($line, $searchText) !== false;
		});
	}

	/**
	 * @return null|string
	 */
	public function getUsesDeclarationLine() {
		return $this->getLineWithText(self::USES_DECLARATION);
	}

	/**
	 * @return null|string
	 */
	public function getTypeDefinitionDeclarationLine() {
		return $this->getLineWithText(self::TYPE_DEFINITION_DECLARATION);
	}

	/**
	 * @param string $original
	 * @param string $new
	 */
	public function replaceTextInStub($original, $new) {
		$this->_content = str_replace($original, $new, $this->_content);

		$this->splitContentAsLines();
	}

	private function splitContentAsLines() {
		$this->_contentAsLines = explode("\n", $this->_content);
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @return string[]
	 */
	public function getContentAsLines() {
		return $this->_contentAsLines;
	}
}