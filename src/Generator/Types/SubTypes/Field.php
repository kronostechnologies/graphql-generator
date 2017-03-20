<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class Field {
	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @var string|null
	 */
	protected $_description;

	/**
	 * @var TypeUsage
	 */
	protected $_fieldType;

	/**
	 * @var FieldArgument[]
	 */
	protected $_arguments = [];

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string|null $description
	 * @param TypeUsage $fieldType
	 * @param FieldArgument[] $arguments
	 */
	public function __construct($name, $description, $fieldType, Array $arguments) {
		$this->setName($name);
		$this->setDescription($description);
		$this->setFieldType($fieldType);
		$this->setArguments($arguments);
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
	 * @return TypeUsage
	 */
	public function getFieldType() {
		return $this->_fieldType;
	}

	/**
	 * @param TypeUsage $fieldType
	 */
	public function setFieldType($fieldType) {
		$this->_fieldType = $fieldType;
	}

	/**
	 * @return FieldArgument[]
	 */
	public function getArguments() {
		return $this->_arguments;
	}

	/**
	 * @param FieldArgument[] $arguments
	 */
	public function setArguments(Array $arguments) {
		$this->_arguments = $arguments;
	}
}