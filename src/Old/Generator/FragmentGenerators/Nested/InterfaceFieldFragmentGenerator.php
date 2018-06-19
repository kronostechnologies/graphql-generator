<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\ResolveFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;

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
		$args = $this->getArgsFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$typeDeclarationFragment, $descriptionFragment, $resolver, $args]);

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
	 * @return FieldArgumentFragmentGenerator[]
	 */
	protected function getFieldArgumentsGenerators() {
		return array_map(function ($fieldArgument) {
			$enumValueGenerator = new FieldArgumentFragmentGenerator();
			$enumValueGenerator->setFormatter($this->getFormatter());
			$enumValueGenerator->setFieldArgumentType($fieldArgument);

			return $enumValueGenerator;
		}, $this->getInterfaceFieldType()->getArguments());
	}

	/**
	 * @return string
	 */
	private function getArgsFragment() {
		$args = $this->getFieldArgumentsGenerators();

		if (empty($args)) {
			return "";
		}

		$argsTypeDefinitions = array_map(function (FieldArgumentFragmentGenerator $argumentGenerator) {
			return $argumentGenerator->generateTypeDefinition();
		}, $args);
		$argsTypeDefinitionsJoined = implode(",", $argsTypeDefinitions);

		return "'args' => [{$argsTypeDefinitionsJoined}]";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getInterfaceFieldType()->getName();
	}
}