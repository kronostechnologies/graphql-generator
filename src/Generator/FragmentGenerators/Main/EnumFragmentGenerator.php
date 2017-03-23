<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\Nested\EnumValueFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;

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
		$nameFragment = $this->getNameFragment($this->getEnumType()->getName());
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
}