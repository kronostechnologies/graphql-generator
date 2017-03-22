<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumType;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumFragmentGenerator implements FragmentGeneratorInterface, VariablesDefiningGeneratorInterface {
	use FormatterDependantGeneratorTrait;

	const ENUM_VAL_PREFIX = 'VAL_';

	/**
	 * @var EnumInterpretedType
	 */
	protected $_enumType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$nameFragment = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->getDescription());
		$valuesFragment = $this->getValuesFragment();

		$vals = $this->joinArrayFragments([$nameFragment, $formattedDescription, $valuesFragment]);

		return "[{$vals}]";
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarations() {
		if ($this->getStubFormatter()->optimizeEnums) {
			return $this->getVariablesDeclarationsOptimized();
		}
		else {
			return $this->getVariablesDeclarationsStandard();
		}
	}

	/**
	 * @return string
	 */
	protected function getConstantValuesArray() {
		$valuesNames = array_map(function ($value) {
			return $this->getSingleConstantValueEntry($value);
		}, $this->getValues());

		return implode("", $valuesNames);
	}

	/**
	 * @param EnumValue $value
	 * @return string
	 */
	protected function getSingleConstantValueEntry($value) {
		$formattedDescription = $this->getDescriptionFragment($value->getDescription());

		return "'{$value->getName()}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$value->getName()}, {$formattedDescription} ],";
	}

	/**
	 * @return string
	 */
	protected function getValuesFragment() {
		return "'values' => [" . $this->getConstantValuesArray() . "]";
	}

	/**
	 * @return string
	 */
	protected function getVariablesDeclarationsOptimized() {
		$constants = "";
		$i = 1;
		foreach($this->getValues() as $value) {
			$constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->getName()} = {$i};\n";
			$i++;
		}

		return $constants;
	}

	/**
	 * @return string
	 */
	protected function getVariablesDeclarationsStandard() {
		$constants = "";
		foreach($this->getValues() as $value) {
			$constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->getName()} = '{$value->getName()}';\n";
		}

		return $constants;
	}

	/**
	 * @return EnumInterpretedType
	 */
	public function getEnumType() {
		return $this->_enumType;
	}

	/**
	 * @param EnumInterpretedType $enumType
	 */
	public function setEnumType($enumType) {
		$this->_enumType = $enumType;
	}
}