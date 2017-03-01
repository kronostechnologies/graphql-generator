<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;

class PSR4ClassFormatter {
	/**
	 * @var StubFormatter
	 */
	protected $_stubFormatter;

	/**
	 * PSR4Formatter constructor.
	 * @param StubFormatter $stubFormatter
	 */
	public function __construct($stubFormatter) {
		$this->_stubFormatter = $stubFormatter;
	}

	/**
	 * @param string $stubTypeDefinitionLine
	 * @param string $typeDefinitionUnformatted
	 * @return string
	 */
	public function getFormattedTypeDefinition($stubTypeDefinitionLine, $typeDefinitionUnformatted) {
		$typeDefinitionIndentSize = $this->_stubFormatter->guessIndentsSize($stubTypeDefinitionLine);
		$typeDefinitionIndented = $this->_stubFormatter->arrayFormatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndentSize);

		return ltrim($typeDefinitionIndented);
	}

	/**
	 * @param string $stubVariablesDeclarationLine
	 * @param string $variablesDeclarationUnformatted
	 * @return string
	 */
	public function getFormattedVariablesDeclaration($stubVariablesDeclarationLine, $variablesDeclarationUnformatted) {
		$variablesDeclarationIndentSize = $this->_stubFormatter->guessIndentsSize($stubVariablesDeclarationLine);
		$variablesDeclarationIndented = $this->_stubFormatter->indent($variablesDeclarationUnformatted, $variablesDeclarationIndentSize);

		return ltrim($variablesDeclarationIndented);
	}
}