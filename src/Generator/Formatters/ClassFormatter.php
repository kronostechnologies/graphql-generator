<?php


namespace GraphQLGen\Generator\Formatters;


/**
 * Formats a PHP class.
 *
 * Class ClassFormatter
 * @package GraphQLGen\Generator\Formatters
 */
class ClassFormatter {
	const INDENT_TOKENS = '{';
	const UNINDENT_TOKENS = '}';
	const NEWLINE_AFTER_TOKENS = '{';
	const NEWLINE_BEFORE_TOKENS = '}';
	const STR_CONTEXT_TOKENS = '"\'';
	const STR_ESCAPE_TOKENS = "\\";
	const ENDLINE_TOKENS = ";";

	/**
	 * @var bool
	 */
	protected $_useSpaces;

	/**
	 * @var int
	 */
	protected $_tabSize;

	/**
	 * @var string
	 */
	protected $_buffer;

	/**
	 * @return bool
	 */
	public function usesSpaces() {
		return $this->_useSpaces;
	}

	/**
	 * @param bool $useSpaces
	 */
	public function setUseSpaces($useSpaces) {
		$this->_useSpaces = $useSpaces;
	}

	/**
	 * @return string
	 */
	public function getBuffer() {
		return $this->_buffer;
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
	 * @param string $buffer
	 */
	public function setBuffer($buffer) {
		$this->_buffer = $buffer;
	}

	protected function minifyBuffer() {
		$minifiedBuffer = $this->getBuffer();
		$minifiedBuffer = str_replace("\n", "", $minifiedBuffer);

		$this->setBuffer($minifiedBuffer);
	}

	/**
	 * @param int $initialIndentSize
	 * @return string
	 */
	public function format($initialIndentSize = 0) {
		// Minify buffer
		$this->minifyBuffer();

		$bufferSplit = str_split($this->getBuffer());
		$endBuffer = "";

		$escapeString = false;
		$stringContext = false;
		$afterNewLine = true;
		$indentSize = $initialIndentSize;
		$arrayContextEnd = -1;

		// Advance token-by-token
		foreach($bufferSplit as $idx => $char) {
			// If in array context, skip everything
			if ($idx <= $arrayContextEnd) {
				continue;
			}

			// If in string context, upon reaching \\, escape next string token
			if (strpos(self::STR_ESCAPE_TOKENS, $char) !== false) {
				$escapeString = !$escapeString;
				$endBuffer .= $char;

				continue;
			}

			// If not escaping next string token, upon reaching " or ', toggle string context
			if (!$escapeString && strpos(self::STR_CONTEXT_TOKENS, $char) !== false) {
				$stringContext = !$stringContext;
				$endBuffer .= $char;

				continue;
			}

			// If in string context, append the token and stop processing it
			if ($stringContext) {
				$escapeString = false;
				$endBuffer .= $char;

				continue;
			}

			// Upon reaching ;, break to new line
			if (strpos(self::ENDLINE_TOKENS, $char) !== false) {
				$endBuffer .= $char . "\n" . $this->getTab($indentSize);
				$afterNewLine = true;

				continue;
			}

			// Upon reaching {, insert space, write character, increment indent and start a new line
			if (strpos(self::INDENT_TOKENS, $char) !== false) {
				$endBuffer .= "{";
				$indentSize++;
				$endBuffer .= "\n" . $this->getTab($indentSize);
				$afterNewLine = true;

				continue;
			}

			// Upon reaching }, decrement indent, write character, and start a new line
			if (strpos(self::UNINDENT_TOKENS, $char) !== false) {
				$indentSize--;
				$endBuffer .= "\n";
				$endBuffer .= $this->getTab($indentSize) . "}\n";

				continue;
			}

			// Upon reaching [, find ] and apply array formatter to it
			if (strpos("[", $char) !== false) {
				$startIdx = $idx;
				$endIdx = $this->findArrayEndIdx($startIdx);

				$arraySubstr = substr($this->getBuffer(), $startIdx, ($endIdx - $startIdx + 1));

				$arrayFormatter = new GeneratorArrayFormatter($this->usesSpaces(), $this->getTabSize());
				$formattedArray = $arrayFormatter->formatArray($arraySubstr, $indentSize);

				$endBuffer .= trim($formattedArray);
				$arrayContextEnd = $endIdx;

				continue;
			}

			// Don't add blank characters after new line
			if (($afterNewLine && strpos($char, " ") === false) || !$afterNewLine) {
				$endBuffer .= $char;
				$afterNewLine = false;
			}
		}

		return $endBuffer;
	}

	protected function findArrayEndIdx($startPos) {
		$arrayLvl = 0;

		$bufferSplit = str_split(substr($this->getBuffer(), $startPos));

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
	 * @param int $size
	 * @return string
	 */
	protected function getTab($size) {
		if($this->usesSpaces()) {
			return str_repeat(' ', $size * $this->getTabSize());
		}

		return str_repeat("\t", $size);
	}


}