<?php


namespace GraphQLGen\Old\Generator\Interpreters\Nested;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class FieldInterpreter extends NestedTypeInterpreter {
	/**
	 * @param FieldDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return FieldInterpretedType
	 */
	public function generateType() {
		$interpretedType = new FieldInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setFieldType($this->interpretType());
		$interpretedType->setArguments($this->interpretArguments());

		return $interpretedType;
	}

	/**
	 * @return FieldArgumentInterpretedType[]
	 */
	public function interpretArguments() {
		return $this->mapNodeList(function ($argumentNode) {
			$argumentInterpreter = new FieldArgumentInterpreter($argumentNode);

			return $argumentInterpreter->generateType();
		}, $this->_astNode->arguments);
	}

	/**
	 * @return TypeUsageInterpretedType
	 */
	public function interpretType() {
		$typeUsageInterpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $typeUsageInterpreter->generateType();
	}
}