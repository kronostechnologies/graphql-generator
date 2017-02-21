<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\EnumTypeValue;

class EnumTypeInterpreter {

	/**
	 * @var EnumTypeDefinitionNode
	 */
	protected $_astNode;

	/**
	 * @param EnumTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return EnumTypeValue[]
	 */
	public function getEnumValues() {
		$enumValues = [];

		foreach($this->_astNode->values as $possibleValue) {
			$enumValues[] = new EnumTypeValue(
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
}