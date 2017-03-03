<?php


namespace GraphQLGen\Generator\Formatters;


/**
 * Formats a PHP class.
 *
 * Class ClassFormatter
 * @package GraphQLGen\Generator\Formatters
 */
class ClassFormatter {
	const STR_CONTEXT_TOKENS = '"\'';
	const STR_ESCAPE_TOKENS = "\\";

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
	public function isUseSpaces() {
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

	protected function minifyBuffer() {
		// ToDo: Remove all \n and spaces from code
	}

	public function format() {
		$this->minifyBuffer();

		$bufferSplit = str_split($this->getBuffer());
		$endBuffer = "";

		$escapeString = false;
		$stringContext = false;

		foreach($bufferSplit as $char) {
			// If in string context, upon reaching \\, escape next string token
			if (strpos(self::STR_ESCAPE_TOKENS, $char)) {
				$escapeString = !$escapeString;
				$endBuffer .= $char;

				continue;
			}

			// If not escaping next string token, upon reaching " or ', toggle string context
			if (!$escapeString && strpos(self::STR_CONTEXT_TOKENS, $char)) {
				$stringContext = !$stringContext;
				$endBuffer .= $char;

				continue;
			}

			// If in string context, append the token and stop processing it
			if ($stringContext) {
				$endBuffer .= $char;

				continue;
			}
		}
	}

	// Minify buffer
	// Advance token-by-token
	// Upon reaching ;, break to new line
	// Upon reaching {, insert space, write character, increment indent and start a new line
	// Upon reaching }, decrement indent, write character, and start a new line
	// Upon reaching [, find ] and apply array formatter to it
	// Insert space before and after =
}