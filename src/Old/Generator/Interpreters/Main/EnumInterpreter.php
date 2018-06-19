<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

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
	 * @return EnumValueInterpretedType[]
	 */
	public function interpretValues() {
		$enumValues = [];

		foreach($this->_astNode->values as $value) {
			$enumValue = new EnumValueInterpretedType();
			$enumValue->setName($value->name->value);
			$enumValue->setDescription($value->description);

			$enumValues[] = $enumValue;
		}

		return $enumValues;
	}
}