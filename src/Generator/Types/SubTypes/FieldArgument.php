<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class FieldArgument {
	/**
	 * @var mixed
	 */
	protected $_defaultValue;

	/**
	 * @var string
	 */
	protected $_description;

	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @var TypeUsage
	 */
	protected $_type;

	/**
	 * FieldArgument constructor.
	 * @param string $name
	 * @param string $description
	 * @param TypeUsage $type
	 * @param string $defaultValue
	 */
	public function __construct($name, $description, $type, $defaultValue) {
		$this->setDescription($description);
		$this->setName($name);
		$this->setType($type);
		$this->setDefaultValue($defaultValue);
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->_defaultValue;
	}

	/**
	 * @param mixed $defaultValue
	 */
	public function setDefaultValue($defaultValue) {
		$this->_defaultValue = $defaultValue;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->_description = $description;
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
	 * @return TypeUsage
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @param TypeUsage $type
	 */
	public function setType($type) {
		$this->_type = $type;
	}
}