<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators\Main;


use GraphQLGen\Old\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\Nested\EnumValueFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;

class EnumFragmentGenerator implements FragmentGeneratorInterface, VariablesDefiningGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait, DescriptionFragmentTrait;

	/**
	 * @var EnumInterpretedType
	 */
	protected $_enumType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$nameFragment = $this->getNameFragment($this->getName());
		$descriptionFragment = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getEnumType()->getDescription()
		);
		$valuesFragment = $this->getValuesFragment();

		$vals = $this->getFormatter()->joinArrayFragments([
			$nameFragment,
			$descriptionFragment,
			$valuesFragment
		]);

		return "[{$vals}]";
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarations() {
		$valuesGenerators = $this->getEnumValuesGenerators();
		$valuesGeneratorsVariables = array_map(function (EnumValueFragmentGenerator $valueGenerator) {
			return $valueGenerator->getVariablesDeclarations();
		}, $valuesGenerators);
		$valuesGeneratorsVariablesJoined = implode(" ", $valuesGeneratorsVariables);

		return $valuesGeneratorsVariablesJoined;
	}

	/**
	 * @return EnumValueFragmentGenerator[]
	 */
	protected function getEnumValuesGenerators() {
		return array_map(function ($enumValueFragment) {
			$enumValueGenerator = new EnumValueFragmentGenerator();
			$enumValueGenerator->setFormatter($this->getFormatter());
			$enumValueGenerator->setEnumValue($enumValueFragment);

			return $enumValueGenerator;
		}, $this->getEnumType()->getValues());
	}

	/**
	 * @return string
	 */
	protected function getValuesFragment() {
		$valuesGenerators = $this->getEnumValuesGenerators();
		$valuesGeneratedTypes = array_map(function (EnumValueFragmentGenerator $valueGenerator) {
			return $valueGenerator->generateTypeDefinition();
		}, $valuesGenerators);
		$valuesGeneratedTypesDefinitionJoined = implode(",", $valuesGeneratedTypes);

		return "'values' => [{$valuesGeneratedTypesDefinitionJoined}]";
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

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getEnumType()->getName();
	}
}