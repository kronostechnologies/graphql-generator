<?php


namespace GraphQLGen\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\ResolveFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;

class InputFieldFragmentGenerator implements FragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, DescriptionFragmentTrait, ResolveFragmentTrait, TypeDeclarationFragmentTrait;

	/**
	 * @var InputFieldInterpretedType
	 */
	protected $_inputFieldType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$typeDeclaration = $this->getTypeDeclarationFragment($this->getFormatter(), $this->getInputFieldType()->getFieldType());
		$formattedDescription = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getInputFieldType()->getDescription()
		);
		$resolver = $this->getResolveFragment(
			$this->getFormatter(),
			$this->getInputFieldType()->getFieldType(),
			$this->getInputFieldType()->getName()
        );

		$vals = $this->getFormatter()->joinArrayFragments([
			$typeDeclaration,
			$formattedDescription,
			$resolver
		]);

		return "'{$this->getInputFieldType()->getName()}' => [{$vals}]";
	}

	/**
	 * @return InputFieldInterpretedType
	 */
	public function getInputFieldType() {
		return $this->_inputFieldType;
	}

	/**
	 * @param InputFieldInterpretedType $inputFieldType
	 */
	public function setInputFieldType($inputFieldType) {
		$this->_inputFieldType = $inputFieldType;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getInputFieldType()->getName();
	}
}
