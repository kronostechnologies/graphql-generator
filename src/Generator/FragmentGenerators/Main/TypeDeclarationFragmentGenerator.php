<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\DependentFragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\Nested\TypeDeclarationFieldFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;

class TypeDeclarationFragmentGenerator implements FragmentGeneratorInterface, DependentFragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait, DescriptionFragmentTrait;

	/**
	 * @var TypeDeclarationInterpretedType
	 */
	protected $_typeDeclaration;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment($this->getTypeDeclaration()->getName());
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getTypeDeclaration()->getDescription()
		);
		$fieldsDefinitions = $this->getFieldsFragment();
		$interfacesDeclaration = $this->getInterfacesFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$name, $formattedDescription, $fieldsDefinitions, $interfacesDeclaration]);

		return "[ {$vals}  ]";
	}

	/**
	 * @return string
	 */
	protected function getInterfacesFragment() {
		if (!empty($this->getTypeDeclaration()->getInterfacesNames())) {
			$interfaceNamesFormatted = array_map(function ($interfaceName) {
				return $this->_formatter->getFieldTypeDeclarationNonPrimaryType($interfaceName);
			}, $this->getTypeDeclaration()->getInterfacesNames());

			$joinedInterfaceNames = implode(", ", $interfaceNamesFormatted);

			return "'interfaces' => [{$joinedInterfaceNames}]";
		}
		else {
			return "";
		}
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = $this->getTypeDeclaration()->getInterfacesNames();

		foreach($this->getTypeDeclaration()->getFields() as $field) {
			$fieldDependencies = $field->getFieldType()->getDependencies();
			$dependencies = array_merge($dependencies, $fieldDependencies);
		}

		return array_unique($dependencies);
	}

	/**
	 * @return TypeDeclarationInterpretedType
	 */
	public function getTypeDeclaration() {
		return $this->_typeDeclaration;
	}

	/**
	 * @param TypeDeclarationInterpretedType $typeDeclaration
	 */
	public function setTypeDeclaration($typeDeclaration) {
		$this->_typeDeclaration = $typeDeclaration;
	}

	/**
	 * @return string
	 */
	protected function getFieldsFragment() {
		return "'fields' => [" . $this->getFieldsDefinitions() . "]";
	}

	/**
	 * @return string
	 */
	protected function getFieldsDefinitions() {
		$fields = $this->getTypeDeclarationFieldsGenerators();
		$fieldsTypeDeclarations = array_map(function (TypeDeclarationFieldFragmentGenerator $fieldGenerator) {
			return $fieldGenerator->generateTypeDefinition();
		}, $fields);
		$fieldsTypeDeclarationsJoined = implode(",", $fieldsTypeDeclarations);

		return $fieldsTypeDeclarationsJoined;
	}

	/**
	 * @return TypeDeclarationFieldFragmentGenerator[]
	 */
	protected function getTypeDeclarationFieldsGenerators() {
		return array_map(function ($typeDeclaration) {
			$typeDeclarationFieldGenerator = new TypeDeclarationFieldFragmentGenerator();
			$typeDeclarationFieldGenerator->setFormatter($this->getFormatter());
			$typeDeclarationFieldGenerator->setTypeDeclarationFieldType($typeDeclaration);

			return $typeDeclarationFieldGenerator;
		}, $this->getTypeDeclaration()->getFields());
	}
}