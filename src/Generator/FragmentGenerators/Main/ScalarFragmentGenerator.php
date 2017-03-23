<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\FragmentGenerators\FormatterDependantGeneratorTrait;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\NameFragmentTrait;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;

class ScalarFragmentGenerator implements FragmentGeneratorInterface {
	use FormatterDependantGeneratorTrait, NameFragmentTrait;

	/**
	 * @var ScalarInterpretedType
	 */
	protected $_scalarType;

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$nameFragment = $this->getNameFragment($this->getScalarType()->getName());
		$descriptionFragment = $this->getDescriptionFragment($this->getScalarType()->getDescription());

		return "{$nameFragment}{$descriptionFragment}";
	}

	/**
	 * @param string $description
	 * @return string
	 */
	protected function getDescriptionFragment($description) {
		if (empty($description)) {
			return "";
		}
		else {
			return "\$this->description = '" . $this->getFormatter()->standardizeDescription($description) . "';";
		}
	}

	/**
	 * @return ScalarInterpretedType
	 */
	public function getScalarType() {
		return $this->_scalarType;
	}

	/**
	 * @param ScalarInterpretedType $scalarType
	 */
	public function setScalarType($scalarType) {
		$this->_scalarType = $scalarType;
	}
}