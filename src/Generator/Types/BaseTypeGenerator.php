<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;

abstract class BaseTypeGenerator {
	/**
	 * @var null|string
	 */
	protected $_description;
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;
	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @return string
	 */
	public abstract function generateTypeDefinition();

	/**
	 * @return string[]
	 */
	public abstract function getDependencies();

	/**
	 * @return null|string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param null|string $description
	 */
	public function setDescription($description) {
		$this->_description = $description;
	}

	/**
	 * @return StubFormatter
	 */
	public function getFormatter() {
		return $this->_formatter;
	}

	/**
	 * @param StubFormatter $formatter
	 */
	public function setFormatter($formatter) {
		$this->_formatter = $formatter;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->_name = $name;
	}

	/**
	 * @return string|null
	 */
	public abstract function getVariablesDeclarations();

	/**
	 * @param string $description
	 * @return string
	 */
	protected function getDescriptionFragment($description) {
		if (empty($description)) {
			return "";
		}
		else {
			return "'description' => '" . $this->_formatter->standardizeDescription($description) . "'";
		}
	}

	/**
	 * @return string
	 */
	protected function getNameFragment() {
		return "'name' => '{$this->_name}'";
	}

	/**
	 * @param string[] $fragments
	 * @return string
	 */
	protected function joinArrayFragments($fragments) {
		$commaSplitVals = array_filter($fragments);

		return implode(",", $commaSplitVals);
	}
}