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
		$formattedDescription = $this->formatter->getDescriptionValue($this->description);
		$fieldsDefinitions = $this->getFieldsDefinitions();

		return "[ 'name' => '{$this->name}',{$formattedDescription} 'fields' => [$fieldsDefinitions], ]";
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
			$typeDeclaration = $this->formatter->fieldTypeFormatter->getFieldTypeDeclaration($field->fieldType);
			$formattedDescription = $this->formatter->getDescriptionValue($field->description);
			$resolver = $this->formatter->fieldTypeFormatter->getResolveSnippet($field);
			$resolver = "'resolver' => function (\$root, \$args) { \$this->resolver->resolve{$field->name}(\$root, \$args); }";

			$fields[] = "'{$field->name}' => [ 'type' => {$typeDeclaration},{$resolver},{$formattedDescription}],";
		}

		return implode('', $fields);
	}
}