<?php


namespace GraphQLGen\Generator\Formatters;


use GraphQLGen\Generator\InterpretedTypes\InterpretedTypesStore;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Generator\Writer\BaseTypeFormatter;

class StubFormatter {
	/**
	 * @var GeneratorArrayFormatter
	 */
	public $arrayFormatter;
	/**
	 * @var string
	 */
	public $descriptionLineMergeChars;
    /**
     * @var bool
     */
	public $optimizeEnums;
    /**
     * @var bool
     */
	public $longFormEnums;
	/**
	 * @var int
	 */
	public $tabSize;
	/**
	 * @var bool
	 */
	public $useSpaces;
	/**
	 * @var BaseTypeFormatter
	 */
	private $_fieldTypeFormatter;

	/**
	 * @param bool $useSpaces
	 * @param int $tabSize
	 * @param string $descriptionLineMergeChars
	 * @param BaseTypeFormatter|null $fieldTypeFormatter
	 * @param bool $optimizeEnums
	 * @param bool $longFormEnums
	 */
	public function __construct($useSpaces = true, $tabSize = 4, $descriptionLineMergeChars = ",", $fieldTypeFormatter = null, $optimizeEnums = false, $longFormEnums = false) {
		$this->descriptionLineMergeChars = $descriptionLineMergeChars;
		$this->useSpaces = $useSpaces;
		$this->tabSize = $tabSize;
		$this->_fieldTypeFormatter = $fieldTypeFormatter;
		$this->arrayFormatter = new GeneratorArrayFormatter($useSpaces, $tabSize);
		$this->optimizeEnums = $optimizeEnums;
		$this->longFormEnums = $longFormEnums;
		$this->_interpretedTypesStore = new InterpretedTypesStore();
	}

	/**
	 * @param TypeUsageInterpretedType $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		return $this->_fieldTypeFormatter->getFieldTypeDeclaration($fieldType);
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
	public function getResolveFragment($typeName) {
		return $this->_fieldTypeFormatter->getResolveSnippet($typeName);
	}

	public function getResolveFragmentForUnion() {
        return $this->_fieldTypeFormatter->getResolveSnippetForUnion();
    }

    public function getInterfaceResolveFragment() {
		return $this->_fieldTypeFormatter->getInterfaceResolveSnippet();
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
	 * @param string $text
	 * @param int $tabsCount
	 * @return string
	 */
	public function indent($text, $tabsCount) {
		$lines = explode(PHP_EOL, $text);
		$tabbedLines = array_map(function ($line) use ($tabsCount) {
			return $this->getTab($tabsCount) . $line;
		}, $lines);

		return implode(PHP_EOL, $tabbedLines);
	}

	/**
	 * @param string $description
	 * @return string
	 */
	public function standardizeDescription($description) {
		$trimmedDescription = trim($description);
		$singleLineDescription = str_replace(PHP_EOL, $this->descriptionLineMergeChars, $trimmedDescription);
		$descriptionSlashed = addslashes($singleLineDescription);

		return $descriptionSlashed;
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
	 * @param string[] $fragments
	 * @return string
	 */
	public function joinArrayFragments($fragments) {
		$commaSplitVals = array_filter($fragments);

		return implode(",", $commaSplitVals);
	}

	/**
	 * @param string $typeName
	 * @return bool
	 */
	public function canInterpretedTypeSkipResolver($typeName) {
		$interpretedType = $this->getInterpretedTypeStore()->getInterpretedTypeByName($typeName);

		return
			($interpretedType instanceof ScalarInterpretedType) ||
			($interpretedType instanceof EnumInterpretedType);
	}

	/**
	 * @var InterpretedTypesStore
	 */
	protected $_interpretedTypesStore;

	/**
	 * @param InterpretedTypesStore $interpretedTypesStore
	 */
	public function setInterpretedTypesStore($interpretedTypesStore) {
		$this->_interpretedTypesStore = $interpretedTypesStore;
	}

	/**
	 * @return InterpretedTypesStore
	 */
	public function getInterpretedTypeStore() {
		return $this->_interpretedTypesStore;
	}
}