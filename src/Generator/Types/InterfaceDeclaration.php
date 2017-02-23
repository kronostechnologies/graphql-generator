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
	 * @param string $description
	 */
	public function __construct($name, $fields, $formatter, $description) {
		$this->name = $name;
		$this->fields = $fields;
		$this->formatter = $formatter;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		return "[ 'name' => '{$this->name}',{$this->formatter->getDescriptionValue($this->description)} 'fields' => [{$this->getFieldsDefinitions()}], ];";
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->fields as $field) {
			$typeDeclaration = $this->formatter->fieldTypeFormatter->getFieldTypeDeclaration($field->fieldType);

			$fields[] = "'{$field->name}' => [ 'type' => {$typeDeclaration},{$this->formatter->getDescriptionValue($field->description)}],";
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
	public function getConstantsDeclaration() {
		return null;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach ($this->fields as $field) {
			$dependencies = array_merge($dependencies, $field->fieldType->getDependencies());
		}

		return array_unique($dependencies);
	}
}