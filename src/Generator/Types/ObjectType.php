<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\ObjectTypeField;

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
	 * @var ObjectTypeField[]
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
	 * @param ObjectTypeField[] $fields
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
		return "
            [
                'name' => '{$this->name}',{$this->formatter->getDescriptionLine($this->description)}
                'fields' => [{$this->getFieldsDefinitions()}
                ],
            ]
        ";
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->fields as $field) {
			$typeDeclaration = $field->fieldType->getFieldTypeDeclaration();

			$fields[] = "
                    '{$field->name}' => [
                        'type' => {$typeDeclaration},{$this->formatter->getDescriptionLine($field->description, 6)}
                    ],";
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