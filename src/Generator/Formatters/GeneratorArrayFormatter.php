<?php


namespace GraphQLGen\Generator\Formatters;


class GeneratorArrayFormatter {
	const INDENT_TOKENS = '[{';
	const UNINDENT_TOKENS = ']}';
	const NEWLINE_AFTER_TOKENS = '[{,';
	const NEWLINE_BEFORE_TOKENS = ']}';
	const STR_CONTEXT_TOKENS = '"\'';
	const STR_ESCAPE_TOKENS = "\\";
	const FUNCTION_ARGS_TOKENS = "()";
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
	 * GeneratorArrayFormatter constructor.
	 * @param bool $useSpaces
	 * @param int $tabSize
	 */
	public function __construct($useSpaces = true, $tabSize = 4) {
		$this->useSpaces = $useSpaces;
		$this->tabSize = $tabSize;
	}

	/**
	 * @param string $textContent
	 * @param int $initialIndentLevel
	 * @return string
	 */
	public function formatArray($textContent, $initialIndentLevel = 0) {
		$context = new GeneratorArrayContext($initialIndentLevel);

		$textContentSplit = str_split($textContent);
		$context->appendCharacter($this->getTab($context->getIndentLevel()) . "");

		foreach($textContentSplit as $char) {
			// Interpret a single token. && is used a char ending so if any value returns false, the current token
			// interpretation will be stopped.
			$this->skipIfAfterNewLineAndIsIgnoredToken($context, $char) &&
			$this->setNotAfterNewLineAndContinue($context) &&
			$this->toggleStringContextAndStopIfStringToken($context, $char) &&
			$this->stopAndEscapeNextCharIfInStringContextAndTokenMatches($context, $char) &&
			$this->escapeCurrentCharAndStopIfRequestedTo($context, $char) &&
			$this->addCharacterAndStopIfInStringContext($context, $char) &&
			$this->toggleFunctionContext($context, $char) &&
			$this->skipCharacterIfInFunctionArgs($context, $char) &&
			$this->increaseIndentLevelIfIndentTokenFound($context, $char) &&
			$this->decreaseIndentLevelIfUnindentTokenFound($context, $char) &&
			$this->increaseIndentBeforeNewLineAndToggleNewLineContext($context, $char) &&
			$this->appendCharacterAndContinue($context, $char) &&
			$this->increaseIndentAfterNewLineAndToggleNewLineContext($context, $char);
		}

		// rtrim & remove blank lines
		$contentAsLines = explode("\n", $context->getBuffer());
		$this->rtrimLines($contentAsLines);
		$this->removeBlankLines($contentAsLines);

		return implode("\n", $contentAsLines);
	}

	/**
	 * @param int $size
	 * @return string
	 */
	protected function getTab($size) {
		if($this->useSpaces) {
			return str_repeat(' ', $size * $this->tabSize);
		}
		else {
			return str_repeat("\t", $size);
		}
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function skipIfAfterNewLineAndIsIgnoredToken($context, $char) {
		return !($context->isAfterNewLine() && strrpos(self::IGNORE_AFTER_NEW_LINE_TOKENS, $char) !== false);
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @return bool
	 */
	private function setNotAfterNewLineAndContinue($context) {
		$context->setIsAfterNewLine(false);

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function toggleStringContextAndStopIfStringToken($context, $char) {
		if(!$context->doEscapeNext() && strrpos(self::STR_CONTEXT_TOKENS, $char) !== false) {
			$context->toggleStringContext();
			$context->appendCharacter($char);
			return false;
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function stopAndEscapeNextCharIfInStringContextAndTokenMatches($context, $char) {
		if(!$context->doEscapeNext() && $context->isInStringContext() && strrpos(self::STR_ESCAPE_TOKENS, $char) !== false) {
			$context->toggleDoEscapeNext();
			$context->appendCharacter($char);
			return false;
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function escapeCurrentCharAndStopIfRequestedTo($context, $char) {
		if($context->doEscapeNext()) {
			$context->toggleDoEscapeNext();
			$context->appendCharacter($char);
			return false;
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function addCharacterAndStopIfInStringContext($context, $char) {
		if($context->isInStringContext()) {
			$context->appendCharacter($char);
			return false;
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function increaseIndentLevelIfIndentTokenFound($context, $char) {
		if(strrpos(self::INDENT_TOKENS, $char) !== false) {
			$context->increaseIndentLevel();
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function decreaseIndentLevelIfUnindentTokenFound($context, $char) {
		if(strrpos(self::UNINDENT_TOKENS, $char) !== false) {
			$context->decreaseIndentLevel();
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function increaseIndentBeforeNewLineAndToggleNewLineContext($context, $char) {
		if(strrpos(self::NEWLINE_BEFORE_TOKENS, $char) !== false) {
			$context->appendCharacter("\n" . $this->getTab($context->getIndentLevel()));
			$context->setIsAfterNewLine(true);
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function appendCharacterAndContinue($context, $char) {
		$context->appendCharacter($char);

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param string $char
	 * @return bool
	 */
	private function increaseIndentAfterNewLineAndToggleNewLineContext($context, $char) {
		if(strrpos(self::NEWLINE_AFTER_TOKENS, $char) !== false) {
			$context->appendCharacter("\n" . $this->getTab($context->getIndentLevel()));
			$context->setIsAfterNewLine(true);
		}

		return true;
	}

	/**
	 * @param string[] $contentAsLines
	 */
	private function rtrimLines(&$contentAsLines) {
		$contentAsLines = array_map(function ($line) {
			return rtrim($line);
		}, $contentAsLines);

	}

	/**
	 * @param string[] $contentAsLines
	 */
	private function removeBlankLines(&$contentAsLines) {
		$contentAsLines = array_filter($contentAsLines, function ($line) {
			return !empty($line);
		});
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param $char
	 * @return bool
	 */
	protected function toggleFunctionContext($context, $char) {
		if (strpos(self::FUNCTION_ARGS_TOKENS, $char) !== false) {
			$context->toggleFunctionArgs();
		}

		return true;
	}

	/**
	 * @param GeneratorArrayContext $context
	 * @param $char
	 * @return bool
	 */
	protected function skipCharacterIfInFunctionArgs($context, $char) {
		if ($context->inFunctionArgs()) {
			$context->appendCharacter($char);

			return false;
		}

		return true;
	}
}