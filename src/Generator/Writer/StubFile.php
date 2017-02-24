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

	/**
	 * @param string $formattedContent
	 */
	public function writeTypeDefinition($formattedContent) {
		$this->replaceTextInStub(StubFile::TYPE_DEFINITION_DECLARATION, $formattedContent);
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeUsesDeclaration($formattedContent) {
		$this->replaceTextInStub(StubFile::USES_DECLARATION, $formattedContent);
	}

	/**
	 * @param string $fileName
	 */
	public function loadFromFile($fileName) {
		$this->_content = file_get_contents($fileName);

		$this->splitContentAsLines();
	}

	private function splitContentAsLines() {
		$this->_contentAsLines = explode("\n", $this->_content);
	}
}