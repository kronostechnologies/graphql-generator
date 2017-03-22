<?php


namespace GraphQLGen\Generator\Formatters;


/**
 * Formats a PHP class.
 *
 * Class ClassFormatter
 * @package GraphQLGen\Generator\Formatters
 */
class ClassFormatter {
	const ENDLINE_TOKENS = ";";
	const INDENT_TOKENS = '{';
	const NEWLINE_AFTER_TOKENS = '{';
	const NEWLINE_BEFORE_TOKENS = '}';
	const STR_CONTEXT_TOKENS = '"\'';
	const STR_ESCAPE_TOKENS = "\\";
	const UNINDENT_TOKENS = '}';
	/**
	 * @var int
	 */
	protected $_tabSize;
	/**
	 * @var bool
	 */
	protected $_useSpaces;

	/**
	 * @param string $classContent
	 * @param int $initialIndentSize
	 * @return string
	 */
	public function format($classContent, $initialIndentSize = 0) {
		$minifiedClassContent = $this->minifyBuffer($classContent);

		// Content can be empty, and str_split will return an empty-valued array instead of null
		if (empty($minifiedClassContent)) {
			return "";
		}

		$bufferSplit = str_split($minifiedClassContent);

		$context = new ClassFormatterContext($initialIndentSize);
		$context->setInitialBuffer($minifiedClassContent);

		// Advance token-by-token
		foreach($bufferSplit as $idx => $char) {
			// Preamble
			$this->skipIfInArray($context, $idx) &&
			$this->indentFirstLine($context, $idx) &&

			// Long comments fragments
			$this->checkLongCommentStartAndSkip($context, $bufferSplit, $idx) &&
			$this->checkLongCommentEndAndSkip($context, $bufferSplit, $idx) &&

			// String context
			$this->toggleStringContext($context, $char) &&
			$this->escapeNextStringToken($context, $char) &&
			$this->appendStringContextTokenAndSkip($context, $char) &&

			// Delimiters
			$this->lineDelimiterNewLine($context, $char) &&
			$this->addOpeningBrace($context, $char) &&
			$this->addClosingBrace($context, $char) &&
			$this->checkForArrayFormatterSection($context, $char, $idx) &&
			$this->addCharIfNotTrimmed($context, $char);
		}

		return rtrim($context->getBuffer());
	}

	/**
	 * @return int
	 */
	public function getTabSize() {
		return $this->_tabSize;
	}

	/**
	 * @param int $tabSize
	 */
	public function setTabSize($tabSize) {
		$this->_tabSize = $tabSize;
	}

	/**
	 * @param bool $useSpaces
	 */
	public function setUseSpaces($useSpaces) {
		$this->_useSpaces = $useSpaces;
	}

	/**
	 * @return bool
	 */
	public function usesSpaces() {
		return $this->_useSpaces;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 */
	protected function addCharIfNotTrimmed(ClassFormatterContext $context, $char) {
		if (($context->isAfterNewLine() && strpos($char, " ") === false) || !$context->isAfterNewLine()) {
			if (!$context->isNewLineIndented()) {
				$context->appendCharacter($this->getTab($context->getIndentLevel()));
				$context->setIsNewLineIndented(true);
			}

			$context->appendCharacter($char);
			$context->setIsAfterNewLine(false);
		}
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function addClosingBrace(ClassFormatterContext $context, $char) {
		if (strpos(self::UNINDENT_TOKENS, $char) !== false) {
			$context->decreaseIndentLevel();

			if (!$context->isNewLineIndented()) {
				$context->appendCharacter($this->getTab($context->getIndentLevel()));
				$context->setIsNewLineIndented(true);
			}

			if ($context->isAfterNewLine()) {
				$context->appendCharacter($char . PHP_EOL);
			} else {
				$context->appendCharacter(PHP_EOL . $this->getTab($context->getIndentLevel()) . $char . PHP_EOL);
			}

			$context->setIsNewLineIndented(false);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function addOpeningBrace(ClassFormatterContext $context, $char) {
		if (strpos(self::INDENT_TOKENS, $char) !== false) {
			$context->appendCharacter($char);
			$context->increaseIndentLevel();
			$context->appendCharacter(PHP_EOL);
			$context->setIsAfterNewLine(true);
			$context->setIsNewLineIndented(false);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function appendStringContextTokenAndSkip(ClassFormatterContext $context, $char) {
		if ($context->isInStringContext()) {
			$context->setDoEscapeNext(false);
			$context->appendCharacter($char);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @param int $idx
	 * @return bool
	 */
	protected function checkForArrayFormatterSection(ClassFormatterContext $context, $char, $idx) {
		if (strpos("[", $char) !== false) {
			$startIdx = $idx;
			$endIdx = $this->findArrayEndIdx($context, $startIdx);

			$arraySubstr = substr($context->getInitialBuffer(), $startIdx, ($endIdx - $startIdx + 1));

			$arrayFormatter = new GeneratorArrayFormatter($this->usesSpaces(), $this->getTabSize());
			$formattedArray = $arrayFormatter->formatArray($arraySubstr, $context->getIndentLevel());

			if (!$context->isNewLineIndented()) {
				$context->appendCharacter($this->getTab($context->getIndentLevel()));
				$context->setIsNewLineIndented(true);
			}

			$context->appendCharacter(trim($formattedArray));
			$context->setArrayContextEnd($endIdx);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string[] $bufferSplit
	 * @param int $idx
	 * @return bool
	 */
	protected function checkLongCommentEndAndSkip($context, $bufferSplit, $idx) {
		if ($context->isInStringContext()) {
			return true;
		}

		if ($context->isInCommentContext()) {
			$chars = $this->getBufferCharsOrNothing($bufferSplit, $idx - 1, 2);

			if ($chars === '*/') {
				$context->toggleCommentContext();

				$context->appendCharacter($bufferSplit[$idx]);
				$context->appendCharacter(PHP_EOL);

				$context->setIsNewLineIndented(false);

				return false;
			}

			$context->appendCharacter($bufferSplit[$idx]);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string[] $bufferSplit
	 * @param int $idx
	 * @return bool
	 */
	protected function checkLongCommentStartAndSkip($context, $bufferSplit, $idx) {
		if ($context->isInStringContext()) {
			return true;
		}

		if (!$context->isInCommentContext()) {
			$chars = $this->getBufferCharsOrNothing($bufferSplit, $idx, 3);

			if ($chars === '/**') {
				$context->toggleCommentContext();

				if ($context->isAfterNewLine()) {
					$context->appendCharacter($this->getTab($context->getIndentLevel()));

					$context->setIsNewLineIndented(true);
				}

				$context->appendCharacter($bufferSplit[$idx]);

				return false;
			}
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function escapeNextStringToken(ClassFormatterContext $context, $char) {
		if ($context->isInStringContext() && strpos(self::STR_ESCAPE_TOKENS, $char) !== false) {
			$context->setDoEscapeNext(true);
			$context->appendCharacter($char);

			return false;
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param int $startPos
	 * @return int
	 */
	protected function findArrayEndIdx(ClassFormatterContext $context, $startPos) {
		$arrayLvl = 0;

		$bufferSplit = str_split(substr($context->getInitialBuffer(), $startPos));

		$stringContext = false;
		$escapeString = false;

		foreach ($bufferSplit as $idx => $char) {
			// If in string context, upon reaching \\, escape next string token
			if (strpos(self::STR_ESCAPE_TOKENS, $char) !== false) {
				$escapeString = !$escapeString;

				continue;
			}

			// If not escaping next string token, upon reaching " or ', toggle string context
			if (!$escapeString && strpos(self::STR_CONTEXT_TOKENS, $char) !== false) {
				$stringContext = !$stringContext;

				continue;
			}

			// If in string context, append the token and stop processing it
			if ($stringContext) {
				$escapeString = false;

				continue;
			}

			if ($char === "]") {
				$arrayLvl--;
			}

			if ($char === "[") {
				$arrayLvl++;
			}

			if ($arrayLvl === 0) {
				return $idx + $startPos;
			}
		}

		return -1;
	}

	/**
	 * @param string[] $bufferSplit
	 * @param int $offset
	 * @param int $length
	 * @return string
	 */
	protected function getBufferCharsOrNothing($bufferSplit, $offset, $length) {
		if ($offset < 0 || count($bufferSplit) < $offset + $length) {
			return "";
		}

		$concatVal = "";
		for ($i = $offset; $i < $offset + $length; $i++) {
			$concatVal .= $bufferSplit[$i];
		}

		return $concatVal;
	}

	/**
	 * @param int $size
	 * @return string
	 */
	protected function getTab($size) {
		if($this->usesSpaces()) {
			return str_repeat(' ', $size * $this->getTabSize());
		}

		return str_repeat("\t", $size);
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function lineDelimiterNewLine(ClassFormatterContext $context, $char) {
		if (strpos(self::ENDLINE_TOKENS, $char) !== false) {
			$context->appendCharacter($char . PHP_EOL);
			$context->setIsAfterNewLine(true);
			$context->setIsNewLineIndented(false);

			return false;
		}

		return true;
	}

	/**
	 * @param string $classContent
	 * @return string
	 */
	protected function minifyBuffer($classContent) {
		$minifiedBuffer = str_replace(PHP_EOL, "", $classContent);

		return $minifiedBuffer;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param int $idx
	 * @return bool
	 */
	protected function skipIfInArray(ClassFormatterContext $context, $idx) {
		return ($idx > $context->getArrayContextEnd());
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param string $char
	 * @return bool
	 */
	protected function toggleStringContext(ClassFormatterContext $context, $char) {
		if (!$context->doEscapeNext() && strpos(self::STR_CONTEXT_TOKENS, $char) !== false) {
			$context->toggleStringContext();
		}

		return true;
	}

	/**
	 * @param ClassFormatterContext $context
	 * @param int $idx
	 * @return bool
	 */
	private function indentFirstLine($context, $idx) {
		if ($idx === 0) {
			$context->appendCharacter($this->getTab($context->getIndentLevel()));
		}

		return true;
	}
}