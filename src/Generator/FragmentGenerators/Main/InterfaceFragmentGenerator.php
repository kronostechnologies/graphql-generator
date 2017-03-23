<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\DependentFragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\Nested\InterfaceFieldFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;

class InterfaceFragmentGenerator implements FragmentGeneratorInterface, DependentFragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait, DescriptionFragmentTrait;

	/**
	 * @var InterfaceDeclarationInterpretedType
	 */
	protected $_interfaceType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment(
			$this->getInterfaceType()->getName()
		);
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getInterfaceType()->getDescription()
		);
		$fields = $this->getFieldsDefinitions();

		$vals = $this->getFormatter()->joinArrayFragments([$name, $formattedDescription, $fields]);

		return "[ {$vals} ]";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach($this->getInterfaceType()->getFields() as $field) {
			$fieldDependencies = $field->getFieldType()->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}

	/**
	 * @return InterfaceDeclarationInterpretedType
	 */
	public function getInterfaceType() {
		return $this->_interfaceType;
	}

	/**
	 * @param InterfaceDeclarationInterpretedType $interfaceType
	 */
	public function setInterfaceType($interfaceType) {
		$this->_interfaceType = $interfaceType;
	}

	/**
	 * @return InterfaceFieldFragmentGenerator[]
	 */
	protected function getInterfaceFieldsGenerators() {
		return array_map(function ($interfaceField) {
			$enumValueGenerator = new InterfaceFieldFragmentGenerator();
			$enumValueGenerator->setFormatter($this->getFormatter());
			$enumValueGenerator->setInterfaceFieldType($interfaceField);

			return $enumValueGenerator;
		}, $this->getInterfaceType()->getFields());
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = $this->getInterfaceFieldsGenerators();
		$fieldsTypesDefinitions = array_map(function (InterfaceFieldFragmentGenerator $fieldGenerator) {
			return $fieldGenerator->generateTypeDefinition();
		}, $fields);
		$fieldsTypesDefinitionsJoined = implode(",", $fieldsTypesDefinitions);

		return $fieldsTypesDefinitionsJoined;
	}
}