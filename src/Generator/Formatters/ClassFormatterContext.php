<?php


namespace GraphQLGen\Generator\Formatters;


class ClassFormatterContext {
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
	 * @var bool
	 */
	protected $_isNewLineIndented = true;

	/**
	 * @var bool
	 */
	protected $_isInCommentContext = false;

	/**
	 * @var int
	 */
	protected $_arrayContextEnd = -1;

	/**
	 * @var string
	 */
	protected $_buffer = '';

	protected $_initialBuffer = '';

	/**
	 * @param int $initialIndentLevel
	 */
	public function __construct($initialIndentLevel) {
		$this->_indentLevel = $initialIndentLevel;
	}

	/**
	 * @param string $buffer
	 */
	public function setInitialBuffer($buffer) {
		$this->_initialBuffer = $buffer;
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

	public function toggleCommentContext() {
		$this->_isInCommentContext = !$this->_isInCommentContext;
	}

	public function setDoEscapeNext($value) {
		$this->_doEscapeNext = $value;
	}

	public function setIsAfterNewLine($value) {
		$this->_isAfterNewLine = $value;
	}

	/**
	 * @return int
	 */
	public function getArrayContextEnd() {
		return $this->_arrayContextEnd;
	}

	/**
	 * @param int $arrayContextEnd
	 */
	public function setArrayContextEnd($arrayContextEnd) {
		$this->_arrayContextEnd = $arrayContextEnd;
	}

	/**
	 * @return string
	 */
	public function getInitialBuffer() {
		return $this->_initialBuffer;
	}

	/**
	 * @return bool
	 */
	public function isNewLineIndented() {
		return $this->_isNewLineIndented;
	}

	/**
	 * @param bool $isNewLineIndented
	 */
	public function setIsNewLineIndented($isNewLineIndented) {
		$this->_isNewLineIndented = $isNewLineIndented;
	}

	/**
	 * @return bool
	 */
	public function isInCommentContext() {
		return $this->_isInCommentContext;
	}
}