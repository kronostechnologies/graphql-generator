<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\AnnotationFormatter;
use GraphQLGen\Generator\Formatters\StubFormatter;

class PSR4ClassFormatter {
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
	 * @param string $stubTypeDefinitionLine
	 * @param string $typeDefinitionUnformatted
	 * @return string
	 */
	public function getFormattedTypeDefinition($stubTypeDefinitionLine, $typeDefinitionUnformatted) {
		$typeDefinitionIndent = $this->_formatter->guessIndentsCount($stubTypeDefinitionLine);

		return ltrim($this->_formatter->arrayFormatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndent));
	}

	/**
	 * @param string $stubVariablesDeclarationLine
	 * @param string $variablesDeclarationUnformatted
	 * @return string
	 */
	public function getFormattedVariablesDeclaration($stubVariablesDeclarationLine, $variablesDeclarationUnformatted) {
		$variablesDeclarationIndent = $this->_formatter->guessIndentsCount($stubVariablesDeclarationLine);

		return ltrim($this->_formatter->indent($variablesDeclarationUnformatted, $variablesDeclarationIndent));
	}

	/**
	 * @param string $typeName
	 * @param bool $isNullable
	 * @param bool $inList
	 * @return string
	 */
	public function getAnnotationForType($typeName, $isNullable, $inList) {
		return $this->_formatter->annotationFormatter->getAnnotationString($typeName, $isNullable, $inList);
	}
}