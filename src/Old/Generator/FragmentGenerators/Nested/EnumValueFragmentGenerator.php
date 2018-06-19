<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

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
		return "const " . self::ENUM_VAL_PREFIX . "{$this->getName()} = '{$this->getName()}';\n";
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		if ($this->getFormatter()->longFormEnums || !empty($this->getEnumValue()->getDescription())) {
			return $this->getLongTypeDefinition();
		}

		return $this->getShortTypeDefinition();
	}

	/**
	 * @return string
	 */
	protected function getShortTypeDefinition() {
		return "self::" . self::ENUM_VAL_PREFIX . $this->getName();
	}

	/**
	 * @return string
	 */
	protected function getLongTypeDefinition() {
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getEnumValue()->getDescription()
		);

		return "'{$this->getName()}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$this->getName()}, {$formattedDescription} ]";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getEnumValue()->getName();
	}
}