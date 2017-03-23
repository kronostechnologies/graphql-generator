<?php


namespace GraphQLGen\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\FragmentGenerators\DefaultValueFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\ResolveFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\TypeDeclarationFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;

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
		$typeDeclaration = $this->getTypeDeclarationFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getFieldType());
		$formattedDescription = $this->getDescriptionFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getDescription());
		$resolver = $this->getResolveFragment($this->getFormatter(), $this->getTypeDeclarationFieldType()->getFieldType(), $this->getTypeDeclarationFieldType()->getName());
		$args = $this->getArgsFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$typeDeclaration, $formattedDescription, $args, $resolver]);

		return "'{$this->getTypeDeclarationFieldType()->getName()}' => [{$vals}]";
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
	 * @return InterfaceFieldFragmentGenerator[]
	 */
	protected function getFieldArgumentsGenerators() {
		return array_map(function ($fieldArgument) {
			$enumValueGenerator = new InterfaceFieldFragmentGenerator();
			$enumValueGenerator->setFormatter($this->getFormatter());
			$enumValueGenerator->setInterfaceFieldType($fieldArgument);

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
}