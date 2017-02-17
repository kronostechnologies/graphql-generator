<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;

class ObjectType implements GeneratorTypeInterface {
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
	public function GenerateTypeDefinition() {
		return "
            [
                'name' => '{$this->name}',{$this->formatter->getDescriptionLine($this->description)}
                'fields' => [{$this->getFieldsDefinition()}
                ],
            ]
        ";
		// TODO: Implement GenerateTypeDefinition() method.

	}

	protected function getFieldsDefinition() {
		$fields = [];

		foreach($this->fields as $field) {
			$typeDeclaration = 'TypeStore::get' . $field->fieldType->typeName . '()';

			// Is base object nullable?
			if(!$field->fieldType->isTypeNullable) {
				$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
			}

			// Is in list?
			if($field->fieldType->inList) {
				$typeDeclaration = 'Type::listOf(' . $typeDeclaration . ')';
			}

			// Is list nullable?
			if($field->fieldType->inList && !$field->fieldType->isListNullable) {
				$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
			}

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
	public function GetStubFile() {
		return __DIR__ . '/stubs/object.stub';
	}

	/**
	 * @return string
	 */
	public function GetNamespacePart() {
		return 'Types';
	}

	/**
	 * @return string
	 */
	public function GetClassName() {
		return $this->name . 'Type';
	}

	/**
	 * @return string
	 */
	public function GetConstantsDeclaration() {
		return null;
	}

	/**
	 * @return string|null
	 */
	public function GetUsesDeclaration() {
		// TODO: Implement GetUsesDeclaration() method.
		return null;
	}
}