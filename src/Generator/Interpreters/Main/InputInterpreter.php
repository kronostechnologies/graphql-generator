<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Generator\Interpreters\Nested\InputFieldInterpreter;

class InputInterpreter extends MainTypeInterpreter {

	/**
	 * @param InputObjectTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return InputInterpretedType
	 */
	public function generateType() {
		$interpretedType = new InputInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setFields($this->interpretFields());

		return $interpretedType;
	}

	/**
	 * @return InputFieldInterpretedType[]
	 */
	public function interpretFields() {
        return $this->mapFieldsNodes(function ($fieldNode) {
            $fieldInterpreter = new InputFieldInterpreter($fieldNode);

            return $fieldInterpreter->generateType();
        });
	}

}