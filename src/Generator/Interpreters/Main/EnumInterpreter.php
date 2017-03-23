<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumInterpreter extends MainTypeInterpreter {
	/**
	 * @param EnumTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return EnumInterpretedType
	 */
	public function generateType() {
		$interpretedType = new EnumInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setValues($this->interpretValues());

		return $interpretedType;
	}

	/**
	 * @return EnumValue[]
	 */
	public function interpretValues() {
		$enumValues = [];

		foreach($this->_astNode->values as $value) {
			$enumValues[] = new EnumValue(
				$value->name->value,
				$value->description
			);
		}

		return $enumValues;
	}
}