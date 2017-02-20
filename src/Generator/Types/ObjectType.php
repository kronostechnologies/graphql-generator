<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\ObjectFieldType;

class ObjectType implements BaseTypeGeneratorInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var null|string
	 */
	public $description;

	/**
	 * @var ObjectFieldType[]
	 */
	public $fields;

	/**
	 * @var StubFormatter
	 */
	public $formatter;

	/**
	 * ObjectType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param ObjectFieldType[] $fields
	 * @param string|null $description
	 */
	public function __construct($name, $formatter, $fields, $description = null) {
		$this->name = $name;
		$this->description = $description;
		$this->fields = $fields;
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
	public function getStubFileName() {
		return '/stubs/object.stub';
	}

	/**
	 * @return string[]
	 */
	public function getDependencyPath() {
		return ['Types'];
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name . 'Type';
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

		foreach ($this->fields as $field) {
			$dependencies = array_merge($dependencies, $field->fieldType->getDependencies());
		}

		return array_unique($dependencies);
	}
}