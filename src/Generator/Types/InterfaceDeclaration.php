<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;

class InterfaceDeclaration implements BaseTypeGeneratorInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var null|string
	 */
	public $description;

	/**
	 * @var StubFormatter
	 */
	public $formatter;

	/**
	 * @var Field[]
	 */
	public $fields;

	/**
	 * InterfaceDeclaration constructor.
	 * @param string $name
	 * @param Field[] $fields
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, $fields, StubFormatter $formatter, $description = null) {
		$this->name = $name;
		$this->fields = $fields ?: [];
		$this->formatter = $formatter;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$formattedDescription = $this->formatter->getDescriptionValue($this->description);
		$fields = $this->getFieldsDefinitions();

		return "[ 'name' => '{$this->name}',{$formattedDescription} 'fields' => [{$fields}], ];";
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->fields as $field) {
			$formattedDescription = $this->formatter->getDescriptionValue($field->description);
			$typeDeclaration = $this->formatter->fieldTypeFormatter->getFieldTypeDeclaration($field->fieldType);

			$fields[] = "'{$field->name}' => [ 'type' => {$typeDeclaration},{$formattedDescription}],";
		}

		return implode('', $fields);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
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

		foreach($this->fields as $field) {
			$fieldDependencies = $field->fieldType->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}
}