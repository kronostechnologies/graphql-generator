<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class Type extends BaseTypeGenerator {
	/**
	 * @var Field[]
	 */
	protected $_fields;

    /**
     * @var \string[]
     */
	protected $_interfacesNames;

	/**
	 * ObjectType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param Field[] $fields
	 * @param string[] $interfaceNames
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, Array $fields, Array $interfaceNames, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setFields($fields);
		$this->setInterfacesNames($interfaceNames);
		$this->setDescription($description);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->getDescription());
		$fieldsDefinitions = $this->getFieldsFragment();
		$interfacesDeclaration = $this->getInterfacesFragment();

		$vals = $this->joinArrayFragments([$name, $formattedDescription, $fieldsDefinitions, $interfacesDeclaration]);

		return "[ {$vals}  ]";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = $this->getInterfacesNames();

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
	public function getInterfacesFragment() {
		if (!empty($this->getInterfacesNames())) {
			$interfaceNamesFormatted = array_map(function ($interfaceName) {
				return $this->_formatter->getFieldTypeDeclarationNonPrimaryType($interfaceName);
			}, $this->getInterfacesNames());

			$joinedInterfaceNames = implode(", ", $interfaceNamesFormatted);

			return "'interfaces' => [{$joinedInterfaceNames}]";
		}
		else {
			return "";
		}
	}

	/**
	 * @return \string[]
	 */
	public function getInterfacesNames() {
		return $this->_interfacesNames;
	}

	/**
	 * @param \string[] $interfacesNames
	 */
	public function setInterfacesNames($interfacesNames) {
		$this->_interfacesNames = $interfacesNames;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string
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
			$typeDeclaration = $this->getTypeDeclarationFragment($field->getFieldType());
			$formattedDescription = $this->getDescriptionFragment($field->getDescription());
			$resolver = $this->getResolveFragment($field);
			$args = $this->getArgsFragment($field);

			$vals = $this->joinArrayFragments([$typeDeclaration, $formattedDescription, $args, $resolver]);

			$fields[] = "'{$field->getName()}' => [{$vals}],";
		}

		return implode('', $fields);
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}

	/**
	 * @param Field $field
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
	 * @param object $value
	 * @return string
	 */
	protected function getDefaultValueFragment($value) {
		if ($value !== null) {
			return "'defaultValue' => '{$value}'";
		}
	}

	/**
	 * @param Field $field
	 * @return string
	 */
	private function getArgsFragment($field) {
		if (count($field->getArguments()) == 0) {
			return "";
		}

		$args = [];

		foreach ($field->getArguments() as $argument) {
			$argType = $this->getTypeDeclarationFragment($argument->getType());
			$defaultValue = $this->getDefaultValueFragment($argument->getDefaultValue());

			$argFragmentsJoined = $this->joinArrayFragments([$argType, $defaultValue]);

			$arg = "'{$argument->getName()}' => [{$argFragmentsJoined}]";

			$args[] = $arg;
		}

		$argsFragmentsJoined = $this->joinArrayFragments($args);

		return "'args' => [{$argsFragmentsJoined}]";
	}
}