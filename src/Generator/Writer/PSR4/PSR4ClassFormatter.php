<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;

class PSR4ClassFormatter {
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;

	/**
	 * @var PSR4StubFile
	 */
	protected $_stubFile;

	/**
	 * PSR4Formatter constructor.
	 * @param StubFormatter $formatter
	 * @param PSR4StubFile $stubFile
	 */
	public function __construct($formatter, $stubFile) {
		$this->_formatter = $formatter;
		$this->_stubFile = $stubFile;
	}

	/**
	 * @param string $typeDefinitionUnformatted
	 * @return string
	 */
	public function getFormattedTypeDefinition($typeDefinitionUnformatted) {
		$typeDefinitionLine = $this->_stubFile->getTypeDefinitionDeclarationLine();
		$typeDefinitionIndent = $this->_formatter->guessIndentsCount($typeDefinitionLine);

		return ltrim($this->_formatter->arrayFormatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndent));
	}

	/**
	 * @param string $variablesDeclarationUnformatted
	 * @return string
	 */
	public function getFormattedVariablesDeclaration($variablesDeclarationUnformatted) {
		$variablesDeclarationLine = $this->_stubFile->getVariablesDeclarationLine();
		$variablesDeclarationIndent = $this->_formatter->guessIndentsCount($variablesDeclarationLine);

		return ltrim($this->_formatter->indent($variablesDeclarationUnformatted, $variablesDeclarationIndent));
	}
}