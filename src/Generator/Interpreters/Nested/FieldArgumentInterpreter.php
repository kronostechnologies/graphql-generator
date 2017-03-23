<?php


namespace GraphQLGen\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldArgumentInterpreter extends NestedTypeInterpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return mixed
	 */
	public function interpretDefaultValue() {
		if($this->_astNode->defaultValue === null) {
			return null;
		}

		return $this->_astNode->defaultValue->value;
	}

	/**
	 * @return TypeUsageInterpretedType
	 */
	public function interpretType() {
		$interpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $interpreter->generateType();
	}

	/**
	 * @return FieldArgumentInterpretedType
	 */
	public function generateType() {
		$interpretedType = new FieldArgumentInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setFieldType($this->interpretType());
		$interpretedType->setDefaultValue($this->interpretDefaultValue());

		return $interpretedType;
	}
}