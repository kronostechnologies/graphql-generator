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

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Enum
	 */
	public function generateType($formatter) {
		return new Enum(
			$this->interpretName(),
			$this->interpretValues(),
			$formatter,
			$this->interpretDescription()
		);
	}
}