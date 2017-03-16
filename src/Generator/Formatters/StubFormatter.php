<?php


namespace GraphQLGen\Generator\Formatters;


use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;

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
	 * @var BaseTypeFormatter
	 */
	public $fieldTypeFormatter;

	/**
	 * @var GeneratorArrayFormatter
	 */
	public $arrayFormatter;

    /**
     * @var bool
     */
	public $optimizeEnums;

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
		$this->fieldTypeFormatter = $fieldTypeFormatter;
		$this->arrayFormatter = new GeneratorArrayFormatter($useSpaces, $tabSize);
		$this->optimizeEnums = $optimizeEnums;
	}

	public function standardizeDescription($description) {
		$trimmedDescription = trim($description);
		$singleLineDescription = str_replace("\n", $this->descriptionLineMergeChars, $trimmedDescription);
		$descriptionSlashed = addslashes($singleLineDescription);

		return $descriptionSlashed;
	}

	public function getDescriptionValue($description) {
		if(!is_null($description)) {
			return "'description' => '{$this->standardizeDescription($description)}'";
		}

		return "";
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
}