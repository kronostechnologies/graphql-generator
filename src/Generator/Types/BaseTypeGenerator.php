<?php


namespace GraphQLGen\Generator\Types;


abstract class BaseTypeGenerator {
	/**
	 * @var \GraphQLGen\Generator\Formatters\StubFormatter
	 */
	protected $_formatter;

	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @var null|string
	 */
	protected $_description;

	/**
	 * @return string
	 */
	public abstract function generateTypeDefinition();

	/**
	 * @return string|null
	 */
	public abstract function getVariablesDeclarations();

	/**
	 * @return string[]
	 */
	public abstract function getDependencies();

	/**
	 * @return \GraphQLGen\Generator\Formatters\StubFormatter
	 */
	public function getFormatter() {
		return $this->_formatter;
	}

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
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
}