<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Types\SubTypes\InputField;

class Input extends BaseTypeGenerator {

	/**
	 * @var InputField[]
	 */
	protected $_fields;

	public function __construct($name, $formatter, $fields, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setFields($fields);
		$this->setDescription($description);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		// TODO: Implement generateTypeDefinition() method.
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		// TODO: Implement getDependencies() method.
	}

	/**
	 * @return InputField[]
	 */
	public function getFields() {
		return $this->_fields;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		// TODO: Implement getVariablesDeclarations() method.
	}

	/**
	 * @param InputField[] $fields
	 */
	public function setFields($fields) {
		$this->_fields = $fields;
	}
}