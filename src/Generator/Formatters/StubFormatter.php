<?php


namespace GraphQLGen\Generator\Formatters;


use GraphQLGen\Generator\Types\SubTypes\TypeFormatter;

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
	 * @var TypeFormatter
	 */
	public $fieldTypeFormatter;

	/**
	 * @var bool
	 */
	public $useConstantsForEnums;

	/**
	 * @var GeneratorArrayFormatter
	 */
	public $arrayFormatter;

	/**
	 * @param bool $useSpaces
	 * @param int $tabSize
	 * @param string $descriptionLineMergeChars
	 * @param TypeFormatter|null $fieldTypeFormatter
	 * @param bool $useConstantsForEnums
	 */
	public function __construct($useSpaces = true, $tabSize = 4, $descriptionLineMergeChars = ",", $fieldTypeFormatter = null, $useConstantsForEnums = true) {
		$this->descriptionLineMergeChars = $descriptionLineMergeChars;
		$this->useSpaces = $useSpaces;
		$this->tabSize = $tabSize;
		$this->fieldTypeFormatter = $fieldTypeFormatter;
		$this->useConstantsForEnums = $useConstantsForEnums;
		$this->arrayFormatter = new GeneratorArrayFormatter($useSpaces, $tabSize);
	}

	public function getDescriptionValue($description) {
		if(!is_null($description)) {
			$trimmedDescription = trim($description);
			$singleLineDescription = str_replace("\n", $this->descriptionLineMergeChars, $trimmedDescription);
			$descriptionSlashed = addslashes($singleLineDescription);

			return "'description' => '{$descriptionSlashed}',";
		}
		else {
			return "";
		}
	}

	/**
	 * @param string $text
	 * @param int $tabsCount
	 * @return string
	 */
	public function indent($text, $tabsCount) {
		$lines = explode("\n", $text);
		$tabbedLines = array_map(function($line) use ($tabsCount) {
			return $this->getTab($tabsCount) . $line;
		}, $lines);

		return implode("\n", $tabbedLines);
	}

	/**
	 * @param string $line
	 * @return int
	 */
	public function guessIndentsCount($line) {
		$regCountSpaces = "/( |\t)/";
		$countMatches = preg_match_all($regCountSpaces, $line, $countSpacesArr);

		if ($this->useSpaces) {
			return intval($countMatches / $this->tabSize);
		}
		else {
			return count($countSpacesArr);
		}
	}

	/**
	 * @param int $size
	 * @return string
	 */
	protected function getTab($size) {
		if ($this->useSpaces) {
			return str_repeat(' ', $size * $this->tabSize);
		}
		else {
			return str_repeat("\t", $size);
		}
	}
}