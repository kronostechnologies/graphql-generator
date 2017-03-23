<?php


namespace GraphQLGen\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

class EnumValueFragmentGenerator implements FragmentGeneratorInterface, VariablesDefiningGeneratorInterface {
	use FormatterDependantGeneratorTrait, DescriptionFragmentTrait;

	const ENUM_VAL_PREFIX = 'VAL_';

	/**
	 * @var EnumValueInterpretedType
	 */
	protected $_enumValue;

	/**
	 * @return EnumValueInterpretedType
	 */
	public function getEnumValue() {
		return $this->_enumValue;
	}

	/**
	 * @param EnumValueInterpretedType $enumValue
	 */
	public function setEnumValue($enumValue) {
		$this->_enumValue = $enumValue;
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarations() {
		return "const " . self::ENUM_VAL_PREFIX . "{$this->getEnumValue()->getName()} = '{$this->getEnumValue()->getName()}';\n";
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getEnumValue()->getDescription()
		);

		return "'{$this->getEnumValue()->getName()}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$this->getEnumValue()->getName()}, {$formattedDescription} ]";
	}
}