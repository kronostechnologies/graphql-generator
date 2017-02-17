<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;

class EnumType implements GeneratorTypeInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var EnumTypeValue[]
	 */
	public $values;

	/**
	 * @var string|null
	 */
	public $description;

	/**
	 * @var StubFormatter
	 */
	public $formatter;

	/**
	 * EnumType constructor.
	 * @param string $name
	 * @param EnumTypeValue[] $values
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, $values, $formatter, $description = null) {
		$this->formatter = $formatter;
		$this->name = $name;
		$this->values = $values;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function GenerateTypeDefinition() {
		$escapedName = addslashes($this->name);
		return "
            [
                'name' => '{$escapedName}',{$this->formatter->getDescriptionLine($this->description, 4)}
                'values' => [{$this->getConstantValuesArray()}
                ]                
            ]";
	}

	protected function getConstantValuesArray() {

		$valuesNames = array_map(function ($value) {
			return $this->getSingleConstantValueEntry($value);
		}, $this->values);

		return implode("", $valuesNames);
	}

	/**
	 * @param EnumTypeValue $value
	 * @return string
	 */
	protected function getSingleConstantValueEntry($value) {
		return "
                    '{$value->name->value}' => [
                        'value' => self::VAL_{$value->name->value},{$this->formatter->getDescriptionLine($value->description, 6)}
                    ],";
	}

	/**
	 * @return string
	 */
	public function GetStubFile() {
		return __DIR__ . '/stubs/enum.stub';
	}

	/**
	 * @return string
	 */
	public function GetNamespacePart() {
		return 'Types\\Enum';
	}

	/**
	 * @return string
	 */
	public function GetClassName() {
		return $this->name . 'Enum';
	}

	/**
	 * @return string|null
	 */
	public function GetConstantsDeclaration() {
		$constants = "";
		foreach($this->values as $value) {
			$constants .=
				"    const VAL_{$value->name->value} = '{$value->name->value}';\n";
		}

		return $constants;
	}

	/**
	 * @return string|null
	 */
	public function GetUsesDeclaration() {
		return null;
	}
}