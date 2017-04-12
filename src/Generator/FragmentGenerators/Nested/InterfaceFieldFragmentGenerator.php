<?php


namespace GraphQLGen\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\ResolveFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;

class InterfaceFieldFragmentGenerator implements FragmentGeneratorInterface  {
	use FormatterDependantGeneratorTrait, DescriptionFragmentTrait, TypeDeclarationFragmentTrait, ResolveFragmentTrait;

	/**
	 * @var FieldInterpretedType
	 */
	protected $_interfaceFieldType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$descriptionFragment = $this->getDescriptionFragment(
			$this->getFormatter(),
			$this->getInterfaceFieldType()->getDescription()
		);
		$typeDeclarationFragment = $this->getTypeDeclarationFragment($this->getFormatter(), $this->getInterfaceFieldType()->getFieldType());
		$resolver = $this->getResolveFragment($this->getFormatter(), $this->getInterfaceFieldType()->getFieldType(), $this->getName());

		$vals = $this->getFormatter()->joinArrayFragments([$typeDeclarationFragment, $descriptionFragment, $resolver]);

		return "'{$this->getInterfaceFieldType()->getName()}' => [ {$vals}]";
	}

	/**
	 * @return FieldInterpretedType
	 */
	public function getInterfaceFieldType() {
		return $this->_interfaceFieldType;
	}

	/**
	 * @param FieldInterpretedType $interfaceFieldType
	 */
	public function setInterfaceFieldType($interfaceFieldType) {
		$this->_interfaceFieldType = $interfaceFieldType;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getInterfaceFieldType()->getName();
	}
}