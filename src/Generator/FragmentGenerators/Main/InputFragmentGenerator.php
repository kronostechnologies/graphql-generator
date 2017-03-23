<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\DependentFragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\Nested\InputFieldFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;

class InputFragmentGenerator implements FragmentGeneratorInterface, DependentFragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait, DescriptionFragmentTrait;

	/**
	 * @var InputInterpretedType
	 */
	protected $_inputType;

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		foreach($this->getInputType()->getFields() as $field) {
			$fieldDependencies = $field->getFieldType()->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment(
			$this->getInputType()->getName()
		);
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getInputType()->getDescription()
		);
		$fieldsDefinitions = $this->getFieldsFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$name, $formattedDescription, $fieldsDefinitions]);

		return "[ {$vals}  ]";
	}

	/**
	 * @return InputInterpretedType
	 */
	public function getInputType() {
		return $this->_inputType;
	}

	/**
	 * @param InputInterpretedType $inputType
	 */
	public function setInputType($inputType) {
		$this->_inputType = $inputType;
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}

	/**
	 * @return InputFieldFragmentGenerator[]
	 */
	protected function getInputFieldsGenerators() {
		return array_map(function ($inputField) {
			$inputFieldGenerator = new InputFieldFragmentGenerator();
			$inputFieldGenerator->setFormatter($this->getFormatter());
			$inputFieldGenerator->setInputFieldType($inputField);

			return $inputFieldGenerator;
		}, $this->getInputType()->getFields());
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = $this->getInputFieldsGenerators();
		$fieldsTypeDefinitions = array_map(function (InputFieldFragmentGenerator $fieldGenerator) {
			return $fieldGenerator->generateTypeDefinition();
		}, $fields);
		$fieldsTypeDefinitionsJoined = implode(",", $fieldsTypeDefinitions);

		return $fieldsTypeDefinitionsJoined;
	}
}