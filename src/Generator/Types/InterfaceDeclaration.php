<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class InterfaceDeclaration extends BaseTypeGenerator {
	/**
	 * @var Field[]
	 */
	protected $_fields;

	/**
	 * InterfaceDeclaration constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param Field[] $fields
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, Array $fields, $description = null) {
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
		$formattedDescription = $this->getDescriptionFragment($this->_description);
		$fields = $this->getFieldsDefinitions();

		$vals = $this->joinArrayFragments([$name, $formattedDescription, $fields]);

		return "[ {$vals} ]";
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
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->getFields() as $field) {
			$descriptionFragment = $this->getDescriptionFragment($field->getDescription());
			$typeDeclarationFragment = $this->getTypeDeclarationFragment($field);
			$resolveFragment = $this->getFormatter()->getResolveFragment($field->getFieldType()->getTypeName());

			$vals = $this->joinArrayFragments([$typeDeclarationFragment, $descriptionFragment, $resolveFragment]);

			$fields[] = "'{$field->getName()}' => [ {$vals}],";
		}

		return implode('', $fields);
	}

	/**
	 * @param Field $field
	 * @return string
	 */
	protected function getTypeDeclarationFragment($field) {
		return "'type' => " . $this->_formatter->getFieldTypeDeclaration($field->getFieldType());
	}
}