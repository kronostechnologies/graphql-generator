<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumInterpreter extends MainTypeInterpreter {
	/**
	 * @param EnumTypeDefinitionNode $astNode
	 */
	public function __construct(EnumTypeDefinitionNode $astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return EnumValue[]
	 */
	public function getEnumValues() {
		$enumValues = [];

		foreach($this->_astNode->values as $possibleValue) {
			$enumValues[] = new EnumValue(
				$possibleValue->name->value,
				$possibleValue->description
			);
		}

		return $enumValues;
	}

	/**
	 * @return string|null
	 */
	public function getDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Enum
	 */
	public function generateType($formatter) {
		return new Enum(
			$this->getName(),
			$this->getEnumValues(),
			$formatter,
			$this->getDescription()
		);
	}
}