<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;

class InterfaceDeclaration extends BaseTypeGenerator {
	/**
	 * @var Field[]
	 */
	protected $_fields;

	/**
	 * InterfaceDeclaration constructor.
	 * @param string $name
	 * @param Field[] $fields
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, Array $fields, StubFormatter $formatter, $description = null) {
		$this->_name = $name;
		$this->_fields = $fields;
		$this->_formatter = $formatter;
		$this->_description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = "'name' => '{$this->_name}'";
		$formattedDescription = $this->getDescriptionFragment($this->_description);
		$fields = $this->getFieldsDefinitions();

		$commaSplitVals = [$name, $formattedDescription, $fields];
		$commaSplitVals = array_filter($commaSplitVals);

		$vals = implode(",", $commaSplitVals);

		return "[ {$vals} ]";
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return $this->_fields;
	}

	/**
	 * @param Field[] $fields
	 */
	public function setFields($fields) {
		$this->_fields = $fields;
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->_fields as $field) {
			$formattedDescription = $this->getDescriptionFragment($field->description);
			$typeDeclaration = "'type' => " . $this->_formatter->getFieldTypeDeclaration($field->fieldType);
			$resolve = $this->_formatter->getResolveSnippet($field->fieldType->typeName);

			$commaSplitVals = [$typeDeclaration, $formattedDescription, $resolve];
			$commaSplitVals = array_filter($commaSplitVals);

			$vals = implode(",", $commaSplitVals);

			$fields[] = "'{$field->name}' => [ {$vals}],";
		}

		return implode('', $fields);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		return null;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach($this->_fields as $field) {
			$fieldDependencies = $field->fieldType->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}
}