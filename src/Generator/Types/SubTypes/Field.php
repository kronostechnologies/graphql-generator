<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class Field {
	/**
	 * @var string
	 */
	public $_name;

	/**
	 * @var string|null
	 */
	public $_description;

	/**
	 * @var TypeUsage
	 */
	public $_fieldType;

	/**
	 * @var FieldArgument[]
	 */
	public $_arguments = [];

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string|null $description
	 * @param TypeUsage $fieldType
	 * @param FieldArgument[] $arguments
	 */
	public function __construct($name, $description, $fieldType, Array $arguments) {
		$this->_name = $name;
		$this->_description = $description;
		$this->_fieldType = $fieldType;
		$this->_arguments = $arguments;
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