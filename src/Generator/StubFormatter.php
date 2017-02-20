<?php


namespace GraphQLGen\Generator;


class StubFormatter {
	const INDENT_TOKENS = '[{';
	const UNINDENT_TOKENS = ']';
	const NEWLINE_AFTER_TOKENS = '[,';
	const NEWLINE_BEFORE_TOKENS = ']';
	const STR_CONTEXT_TOKENS = '"\'';
	const STR_ESCAPE_TOKEN = "\\";
	const IGNORE_AFTER_NEW_LINE_TOKENS = " ";

	/**
	 * @var bool
	 */
	public $useSpaces;

	/**
	 * @var int
	 */
	public $tabSize;

	/**
	 * @param bool $useSpaces
	 * @param int $tabSize
	 */
	public function __construct($useSpaces = true, $tabSize = 4) {
		$this->useSpaces = $useSpaces;
		$this->tabSize = $tabSize;
	}

	public function getDescriptionLine($description, $tabsCount = 4) {
		$indent = str_repeat("    ", $tabsCount);

		if(!is_null($description)) {
			$trimmedDescription = trim($description);
			$singleLineDescription = str_replace("\n", ",", $trimmedDescription);
			$descriptionSlashed = addslashes($singleLineDescription);

			return "\n{$indent}'description' => '{$descriptionSlashed}',";
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
	 * @param string $textContent
	 * @param int $initialIndentLevel
	 * @return string
	 */
	public function formatArray($textContent, $initialIndentLevel = 0) {
		$indentLevel = $initialIndentLevel;
		$inStringContext = false;
		$escapeNext = false;
		$afterNewLine = false;

		$retVal = $this->getTab($indentLevel) . "";
		$textContentSplit = str_split($textContent);

		foreach ($textContentSplit as $char) {
			// Ignore after newline
			if ($afterNewLine && strrpos(self::IGNORE_AFTER_NEW_LINE_TOKENS, $char) !== false) {
				continue;
			}

			// Not anymore after new line
			$afterNewLine = false;

			// String context check
			if (!$escapeNext && strrpos(self::STR_CONTEXT_TOKENS, $char) !== false) {
				$inStringContext = !$inStringContext;
				$retVal .= $char;
				continue;
			}

			// Escape string check
			if (!$escapeNext && $inStringContext && strrpos(self::STR_ESCAPE_TOKEN, $char) !== false) {
				$escapeNext = true;
				$retVal .= $char;
				continue;
			}

			// Escape this character
			if ($escapeNext) {
				$escapeNext = false;
				$retVal .= $char;
				continue;
			}

			// Mindlessly add character if in string context
			if ($inStringContext) {
				$retVal .= $char;
				continue;
			}

			// Indent check
			if (strrpos(self::INDENT_TOKENS, $char) !== false) {
				$indentLevel++;
			}

			// Unindent check
			if (strrpos(self::UNINDENT_TOKENS, $char) !== false) {
				$indentLevel--;
			}

			// Newline (before) check
			if (strrpos(self::NEWLINE_BEFORE_TOKENS, $char) !== false) {
				$retVal .= "\n" . $this->getTab($indentLevel);
				$afterNewLine = true;
			}

			// Add character
			$retVal .= $char;

			// Newline (after) check
			if (strrpos(self::NEWLINE_AFTER_TOKENS, $char) !== false) {
				$retVal .= "\n" . $this->getTab($indentLevel);
				$afterNewLine = true;
			}
		}

		// rtrim lines & remove blank lines
		$linesArray = explode("\n", $retVal);
		$linesArray = array_map(function ($line) { return rtrim($line); }, $linesArray);
		$linesArray = array_filter($linesArray, function ($line) { return !empty($line); });

		return implode("\n", $linesArray);
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