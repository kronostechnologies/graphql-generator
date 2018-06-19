<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\FragmentGenerators\DefaultValueFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;

class FieldArgumentFragmentGenerator implements FragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, DefaultValueFragmentTrait, TypeDeclarationFragmentTrait;

	/**
	 * @var FieldArgumentInterpretedType
	 */
	protected $_fieldArgumentType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$argType = $this->getTypeDeclarationFragment($this->getFormatter(), $this->getFieldArgumentType()->getFieldType());
		$defaultValue = $this->getDefaultValueFragment($this->getFieldArgumentType()->getDefaultValue());

		$argFragmentsJoined = $this->getFormatter()->joinArrayFragments([$argType, $defaultValue]);

		return "'{$this->getFieldArgumentType()->getName()}' => [{$argFragmentsJoined}]";
	}

	/**
	 * @return FieldArgumentInterpretedType
	 */
	public function getFieldArgumentType() {
		return $this->_fieldArgumentType;
	}

	/**
	 * @param FieldArgumentInterpretedType $fieldArgumentType
	 */
	public function setFieldArgumentType($fieldArgumentType) {
		$this->_fieldArgumentType = $fieldArgumentType;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getFieldArgumentType()->getName();
	}
}