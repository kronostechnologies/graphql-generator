<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\FragmentGenerators\DefaultValueFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\ResolveFragmentTrait;
use GraphQLGen\Old\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;

class TypeDeclarationFieldFragmentGenerator implements FragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, DescriptionFragmentTrait, DefaultValueFragmentTrait, ResolveFragmentTrait, TypeDeclarationFragmentTrait;

	/**
	 * @var FieldInterpretedType
	 */
	protected $_typeDeclarationFieldType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$fieldHasArguments = !empty($this->getTypeDeclarationFieldType()->getArguments());

		$typeDeclaration = $this->getTypeDeclarationFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getFieldType());
		$formattedDescription = $this->getDescriptionFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getDescription());
		$resolver = $this->getResolveFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getFieldType(), $this->getName(), $fieldHasArguments);
		$args = $this->getArgsFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$typeDeclaration, $formattedDescription, $args, $resolver]);

		return "'{$this->getName()}' => [{$vals}]";
	}

	/**
	 * @return FieldInterpretedType
	 */
	public function getTypeDeclarationFieldType() {
		return $this->_typeDeclarationFieldType;
	}

	/**
	 * @param FieldInterpretedType $typeDeclarationFieldType
	 */
	public function setTypeDeclarationFieldType($typeDeclarationFieldType) {
		$this->_typeDeclarationFieldType = $typeDeclarationFieldType;
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
		}, $this->getTypeDeclarationFieldType()->getArguments());
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
		return $this->getTypeDeclarationFieldType()->getName();
	}
}