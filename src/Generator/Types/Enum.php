<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class Enum implements BaseTypeGeneratorInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var EnumValue[]
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

	const ENUM_VAL_PREFIX = 'VAL_';

	/**
	 * EnumType constructor.
	 * @param string $name
	 * @param EnumValue[] $values
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, $values, StubFormatter $formatter, $description = null) {
		$this->formatter = $formatter;
		$this->name = $name;
		$this->values = $values;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$formattedDescription = $this->formatter->getDescriptionValue($this->description);
		$values = $this->getConstantValuesArray();

		return "[ 'name' => '{$this->name}', {$formattedDescription} 'values' => [{$values}] ];";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		$constants = "";
		foreach($this->values as $value) {
			$constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->name} = '{$value->name}';\n";
		}

		return $constants;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}

	protected function getConstantValuesArray() {
		$valuesNames = array_map(function ($value) {
			return $this->getSingleConstantValueEntry($value);
		}, $this->values ?: []);

		return implode("", $valuesNames);
	}

	/**
	 * @param EnumValue $value
	 * @return string
	 */
	protected function getSingleConstantValueEntry($value) {
		$formattedDescription = $this->formatter->getDescriptionValue($value->description);

		if($this->formatter->useConstantsForEnums) {
			return "'{$value->name}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$value->name}, {$formattedDescription} ],";
		}

		return "'{$value->name}' => [ 'value' => '{$value->name}', {$formattedDescription} ],";
	}
}