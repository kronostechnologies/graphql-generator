<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Types\SubTypes\InputField;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

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
		$name = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->getDescription());
		$fieldsDefinitions = $this->getFieldsFragment();

		$vals = $this->joinArrayFragments([$name, $formattedDescription, $fieldsDefinitions]);

		return "[ {$vals}  ]";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach($this->getFields() as $field) {
			$fieldDependencies = $field->getFieldType()->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
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
		return null;
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}

	/**
	 * @param InputField $field
	 * @return string
	 */
	protected function getResolveFragment($field) {
		if (!$field->getFieldType()->isPrimaryType()) {
			return "'resolver' => " . $this->getFormatter()->getResolveFragment($field->getName());
		}

		return "";
	}

	/**
	 * @param TypeUsage $type
	 * @return string
	 */
	protected function getTypeDeclarationFragment($type) {
		return "'type' => " . $this->getFormatter()->getFieldTypeDeclaration($type);
	}

	/**
	 * @param InputField[] $fields
	 */
	public function setFields($fields) {
		$this->_fields = $fields;
	}

	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->getFields() as $field) {
			$typeDeclaration = $this->getTypeDeclarationFragment($field->getFieldType());
			$formattedDescription = $this->getDescriptionFragment($field->getDescription());
			$resolver = $this->getResolveFragment($field);

			$vals = $this->joinArrayFragments([$typeDeclaration, $formattedDescription, $resolver]);

			$fields[] = "'{$field->getName()}' => [{$vals}],";
		}

		return implode('', $fields);
	}
}