<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\DependentFragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\DescriptionFragmentTrait;
use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class UnionFragmentGenerator implements FragmentGeneratorInterface, DependentFragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait, DescriptionFragmentTrait;

	/**
	 * @var UnionInterpretedType
	 */
	protected $_unionType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment($this->getName());
		$formattedDescription = $this->getDescriptionFragment($this->getFormatter(), $this->getUnionType()->getDescription());
		$typesDefinitions = $this->getTypesDefinitionsFragment();
		$resolveType = $this->getResolveTypeFragment();

		$vals = $this->getFormatter()->joinArrayFragments([$name, $formattedDescription, $typesDefinitions, $resolveType]);

		return "[{$vals}]";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return array_map(function (TypeUsageInterpretedType $type) {
			return $type->getTypeName();
		}, $this->getUnionType()->getTypes());
	}

	/**
	 * @return UnionInterpretedType
	 */
	public function getUnionType() {
		return $this->_unionType;
	}

	/**
	 * @param UnionInterpretedType $unionType
	 */
	public function setUnionType($unionType) {
		$this->_unionType = $unionType;
	}

	/**
	 * @return string
	 */
	protected function getTypesDefinitionsFragment() {
		$typesDefinitions = array_map(function (TypeUsageInterpretedType $type) {
			return $this->getFormatter()->getFieldTypeDeclaration($type);
		}, $this->getUnionType()->getTypes());

		$typesDefinitionsJoined = $this->getFormatter()->joinArrayFragments($typesDefinitions);

		return "'types' => [{$typesDefinitionsJoined}]";
	}

	/**
	 * @return string
	 */
	protected function getResolveTypeFragment() {
		return "'resolveType' => {$this->getFormatter()->getResolveFragmentForUnion()}";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getUnionType()->getName();
	}
}