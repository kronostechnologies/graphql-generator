<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;

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
		$this->_name = $name;
		$this->_description = $description;
		$this->_fields = $fields;
		$this->_formatter = $formatter;
		$this->_interfacesNames = $interfaceNames;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->_description);
		$fieldsDefinitions = $this->getFieldsFragment();
		$interfacesDeclaration = $this->getInterfacesFragment();

		$commaSplitVals = [$name, $formattedDescription, $fieldsDefinitions, $interfacesDeclaration];
		$commaSplitVals = array_filter($commaSplitVals);

		$vals = implode(",", $commaSplitVals);

		return "[ {$vals}  ]";
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
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = $this->_interfacesNames;

		foreach($this->_fields as $field) {
			$fieldDependencies = $field->fieldType->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}

	/**
	 * @return string
	 */
	public function getInterfacesFragment() {
		if (!empty($this->_interfacesNames)) {
			$interfaceNamesFormatted = array_map(function ($interfaceName) {
				return $this->_formatter->getFieldTypeDeclarationNonPrimaryType($interfaceName);
			}, $this->_interfacesNames);

			$joinedInterfaceNames = implode(", ", $interfaceNamesFormatted);

			return "'interfaces' => [{$joinedInterfaceNames}]";
		}
		else {
			return "";
		}
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return $this->_fields;
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
			$typeDeclaration = "'type' => " . $this->_formatter->getFieldTypeDeclaration($field->fieldType);
			$formattedDescription = $this->getDescriptionFragment($field->description);
			$resolver = $this->_formatter->getResolveSnippet($field->name);

			$commaSplitVals = [$typeDeclaration, $formattedDescription, $resolver];
			$commaSplitVals = array_filter($commaSplitVals);

			$vals = implode(",", $commaSplitVals);

			$fields[] = "'{$field->name}' => [{$vals}],";
		}

		return implode('', $fields);
	}

	/**
	 * @return string
	 */
	protected function getNameFragment() {
		return "'name' => '{$this->_name}'";
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}


}