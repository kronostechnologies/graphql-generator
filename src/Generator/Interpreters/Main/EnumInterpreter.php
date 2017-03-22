<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
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
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Enum
	 */
	public function generateType($formatter) {
		return new Enum(
			$this->interpretName(), $formatter, $this->interpretValues(), $this->interpretDescription()
		);
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