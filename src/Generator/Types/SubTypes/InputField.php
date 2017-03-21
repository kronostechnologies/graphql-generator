<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class InputField {

	/**
	 * @var string|null
	 */
	protected $_description;
	/**
	 * @var TypeUsage
	 */
	protected $_fieldType;
	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string|null $description
	 * @param TypeUsage $fieldType
	 */
	public function __construct($name, $description, $fieldType) {
		$this->setName($name);
		$this->setDescription($description);
		$this->setFieldType($fieldType);
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

}