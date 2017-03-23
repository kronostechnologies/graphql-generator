<?php


namespace GraphQLGen\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\FragmentGenerators\DefaultValueFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;

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