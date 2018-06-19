<?php


namespace GraphQLGen\Old\Generator\Formatters;


class ClassFormatterContext {
	/**
	 * @var int
	 */
	protected $_arrayContextEnd = -1;
	/**
	 * @var string
	 */
	protected $_buffer = '';
	/**
	 * @var bool
	 */
	protected $_doEscapeNext = false;
	/**
	 * @var int
	 */
	protected $_indentLevel;
	/**
	 * @var string
	 */
	protected $_initialBuffer = '';
	/**
	 * @var bool
	 */
	protected $_isAfterNewLine = false;
	/**
	 * @var bool
	 */
	protected $_isInCommentContext = false;
	/**
	 * @var bool
	 */
	protected $_isInStringContext = false;
	/**
	 * @var bool
	 */
	protected $_isNewLineIndented = true;

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
	 * @return string
	 */
	public function getInitialBuffer() {
		return $this->_initialBuffer;
	}

	/**
	 * @param string $buffer
	 */
	public function setInitialBuffer($buffer) {
		$this->_initialBuffer = $buffer;
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
	public function isInCommentContext() {
		return $this->_isInCommentContext;
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
	public function isNewLineIndented() {
		return $this->_isNewLineIndented;
	}

	public function setDoEscapeNext($value) {
		$this->_doEscapeNext = $value;
	}

	public function setIsAfterNewLine($value) {
		$this->_isAfterNewLine = $value;
	}

	/**
	 * @param bool $isNewLineIndented
	 */
	public function setIsNewLineIndented($isNewLineIndented) {
		$this->_isNewLineIndented = $isNewLineIndented;
	}

	public function toggleCommentContext() {
		$this->_isInCommentContext = !$this->_isInCommentContext;
	}

	public function toggleStringContext() {
		$this->_isInStringContext = !$this->_isInStringContext;
	}
}