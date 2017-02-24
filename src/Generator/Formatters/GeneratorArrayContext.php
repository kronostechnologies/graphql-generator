<?php


namespace GraphQLGen\Generator\Formatters;


class GeneratorArrayContext {
	/**
	 * @var int
	 */
	protected $_indentLevel;

	/**
	 * @var bool
	 */
	protected $_isInStringContext = false;

	/**
	 * @var bool
	 */
	protected $_doEscapeNext = false;

	/**
	 * @var bool
	 */
	protected $_isAfterNewLine = false;

	/**
	 * @var string
	 */
	protected $_buffer = '';

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
	public function isInStringContext() {
		return $this->_isInStringContext;
	}

	/**
	 * @return bool
	 */
	public function doEscapeNext() {
		return $this->_doEscapeNext;
	}

	/**
	 * @return bool
	 */
	public function isAfterNewLine() {
		return $this->_isAfterNewLine;
	}

	public function decreaseIndentLevel() {
		$this->_indentLevel--;
	}

	public function increaseIndentLevel() {
		$this->_indentLevel++;
	}

	public function toggleStringContext() {
		$this->_isInStringContext = !$this->_isInStringContext;
	}

	public function toggleDoEscapeNext() {
		$this->_doEscapeNext = !$this->_doEscapeNext;
	}

	public function setIsAfterNewLine($value) {
		$this->_isAfterNewLine = $value;
	}
}