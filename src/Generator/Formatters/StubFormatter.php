<?php


namespace GraphQLGen\Generator\Formatters;


use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class StubFormatter {
	/**
	 * @var string
	 */
	public $descriptionLineMergeChars;

	/**
	 * @var bool
	 */
	public $useSpaces;

	/**
	 * @var int
	 */
	public $tabSize;

	/**
	 * @var GeneratorArrayFormatter
	 */
	public $arrayFormatter;

    /**
     * @var bool
     */
	public $optimizeEnums;

	/**
	 * @var BaseTypeFormatter
	 */
	private $_fieldTypeFormatter;

	/**
	 * @param bool $useSpaces
	 * @param int $tabSize
	 * @param string $descriptionLineMergeChars
	 * @param BaseTypeFormatter|null $fieldTypeFormatter
	 */
	public function __construct($useSpaces = true, $tabSize = 4, $descriptionLineMergeChars = ",", $fieldTypeFormatter = null, $optimizeEnums = false) {
		$this->descriptionLineMergeChars = $descriptionLineMergeChars;
		$this->useSpaces = $useSpaces;
		$this->tabSize = $tabSize;
		$this->_fieldTypeFormatter = $fieldTypeFormatter;
		$this->arrayFormatter = new GeneratorArrayFormatter($useSpaces, $tabSize);
		$this->optimizeEnums = $optimizeEnums;
	}

	/**
	 * @param string $description
	 * @return string
	 */
	public function standardizeDescription($description) {
		$trimmedDescription = trim($description);
		$singleLineDescription = str_replace("\n", $this->descriptionLineMergeChars, $trimmedDescription);
		$descriptionSlashed = addslashes($singleLineDescription);

		return $descriptionSlashed;
	}

	/**
	 * @param string $text
	 * @param int $tabsCount
	 * @return string
	 */
	public function indent($text, $tabsCount) {
		$lines = explode("\n", $text);
		$tabbedLines = array_map(function ($line) use ($tabsCount) {
			return $this->getTab($tabsCount) . $line;
		}, $lines);

		return implode("\n", $tabbedLines);
	}

	/**
	 * @param string $line
	 * @return int
	 */
	public function guessIndentsSize($line) {
		$regCountSpaces = "/( |\t)/";
		$countMatches = preg_match_all($regCountSpaces, $line, $countSpacesArr);

		if($this->useSpaces) {
			return intval($countMatches / $this->tabSize);
		}

		return count($countSpacesArr);
	}

	/**
	 * @param int $size
	 * @return string
	 */
	protected function getTab($size) {
		if($this->useSpaces) {
			return str_repeat(' ', $size * $this->tabSize);
		}

		return str_repeat("\t", $size);
	}

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($typeName) {
		return $this->_fieldTypeFormatter->getFieldTypeDeclarationNonPrimaryType($typeName);
	}

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getResolveSnippet($typeName) {
		return $this->_fieldTypeFormatter->getResolveSnippet($typeName);
	}

	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		return $this->_fieldTypeFormatter->getFieldTypeDeclaration($fieldType);
	}
}