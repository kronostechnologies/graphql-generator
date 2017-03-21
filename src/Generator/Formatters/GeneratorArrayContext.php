<?php


namespace GraphQLGen\Generator\Formatters;


class GeneratorArrayContext {
	/**
	 * @var string
	 */
	protected $_buffer = '';
	/**
	 * @var bool
	 */
	protected $_doEscapeNext = false;
	/**
	 * @var bool
	 */
	protected $_inFunctionArgs = false;
	/**
	 * @var int
	 */
	protected $_indentLevel;
	/**
	 * @var bool
	 */
	protected $_isAfterNewLine = false;
	/**
	 * @var bool
	 */
	protected $_isInStringContext = false;

	/**
	 * @param int $initialIndentLevel
	 */
	public function __construct($initialIndentLevel) {
		$this->_indentLevel = $initialIndentLevel;
	}

	/**
	 * @param string $character
	 */
	public function appendCharacter($character) {
		$this->_buffer .= $character;
	}

	public function decreaseIndentLevel() {
		$this->_indentLevel--;
	}

	/**
	 * @return bool
	 */
	public function doEscapeNext() {
		return $this->_doEscapeNext;
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
	public function getIndentLevel() {
		return $this->_indentLevel;
	}

	/**
	 * @return bool
	 */
	public function inFunctionArgs() {
		return $this->_inFunctionArgs;
	}

	public function increaseIndentLevel() {
		$this->_indentLevel++;
	}

	/**
	 * @return bool
	 */
	public function isAfterNewLine() {
		return $this->_isAfterNewLine;
	}

	/**
	 * @return bool
	 */
	public function isInStringContext() {
		return $this->_isInStringContext;
	}

	/**
	 * @param bool $value
	 */
	public function setIsAfterNewLine($value) {
		$this->_isAfterNewLine = $value;
	}

	public function toggleDoEscapeNext() {
		$this->_doEscapeNext = !$this->_doEscapeNext;
	}

	public function toggleFunctionArgs() {
		$this->_inFunctionArgs = !$this->_inFunctionArgs;
	}

	public function toggleStringContext() {
		$this->_isInStringContext = !$this->_isInStringContext;
	}
}