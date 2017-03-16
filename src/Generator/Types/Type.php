<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;

class Type extends BaseTypeGenerator {
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
     * @var \string[]
     */
	public $interfaceNames;

	/**
	 * ObjectType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param Field[] $fields
	 * @param string[] $interfaceNames
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, Array $fields, Array $interfaceNames, $description = null) {
		$this->name = $name;
		$this->description = $description;
		$this->fields = $fields;
		$this->formatter = $formatter;
		$this->interfaceNames = $interfaceNames;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->description);
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
		$dependencies = $this->interfaceNames;

		foreach($this->fields as $field) {
			$fieldDependencies = $field->fieldType->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}

	/**
	 * @return string
	 */
	public function getInterfacesFragment() {
		if (!empty($this->interfaceNames)) {
			$interfaceNamesFormatted = array_map(function ($interfaceName) {
				return $this->formatter->getFieldTypeDeclarationNonPrimaryType($interfaceName);
			}, $this->interfaceNames);

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
		return $this->fields;
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = [];

		foreach($this->fields as $field) {
			$typeDeclaration = "'type' => " . $this->formatter->getFieldTypeDeclaration($field->fieldType);
			$formattedDescription = $this->getDescriptionFragment($field->description);
			$resolver = $this->formatter->getResolveSnippet($field->name);

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
		return "'name' => '{$this->name}'";
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}


}