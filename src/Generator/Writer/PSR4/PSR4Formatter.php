<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;

class PSR4Formatter {
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;

	/**
	 * PSR4Formatter constructor.
	 * @param StubFormatter $formatter
	 */
	public function __construct($formatter) {
		$this->_formatter = $formatter;
	}

	/**
	 * @param string $typeDefinitionLine
	 * @param string $typeDefinitionUnformatted
	 * @return string
	 */
	public function formatTypeDefinition($typeDefinitionLine, $typeDefinitionUnformatted) {
		$typeDefinitionIndent = $this->_formatter->guessIndentsCount($typeDefinitionLine);

		return ltrim($this->_formatter->arrayFormatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndent));
	}

	/**
	 * @param string $variablesDeclarationLine
	 * @param string $variablesDeclarationUnformatted
	 * @return string
	 */
	public function formatVariablesDeclaration($variablesDeclarationLine, $variablesDeclarationUnformatted) {
		$variablesDeclarationIndent = $this->_formatter->guessIndentsCount($variablesDeclarationLine);

		return ltrim($this->_formatter->indent($variablesDeclarationUnformatted, $variablesDeclarationIndent));
	}
}