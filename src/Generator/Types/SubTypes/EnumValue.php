<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class EnumValue {
	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @var string
	 */
	protected $_description;

	/**
	 * EnumTypeValue constructor.
	 * @param string $name
	 * @param string $description
	 */
	public function __construct($name, $description) {
		$this->_name = $name;
		$this->_description = $description;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param string $_name
	 */
	public function setName($_name) {
		$this->_name = $_name;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param string $_description
	 */
	public function setDescription($_description) {
		$this->_description = $_description;
	}
}