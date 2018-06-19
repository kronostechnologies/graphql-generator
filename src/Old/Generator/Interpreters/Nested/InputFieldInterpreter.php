<?php


namespace GraphQLGen\Old\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class InputFieldInterpreter extends NestedTypeInterpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return InputFieldInterpretedType
	 */
	public function generateType() {
		$interpretedType = new InputFieldInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setFieldType($this->interpretType());

		return $interpretedType;
	}

	/**
	 * @return TypeUsageInterpretedType
	 */
	public function interpretType() {
		$typeUsageInterpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $typeUsageInterpreter->generateType();
	}
}