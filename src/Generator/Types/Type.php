<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;

class Type implements BaseTypeGeneratorInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var null|string
	 */
	public $description;

	/**
	 * @var Field[]
	 */
	public $fields;

	/**
	 * @var \GraphQLGen\Generator\Formatters\StubFormatter
	 */
	public $formatter;

	/**
	 * ObjectType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param Field[] $fields
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, $fields, $description = null) {
		$this->name = $name;
		$this->description = $description;
		$this->fields = $fields ?: [];
		$this->formatter = $formatter;
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
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getConstantsDeclaration() {
		return null;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach($this->fields as $field) {
			$dependencies = array_merge($dependencies, $field->fieldType->getDependencies());
		}

		return array_unique($dependencies);
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
}