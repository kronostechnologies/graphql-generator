<?php


namespace GraphQLGen\Old\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

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