<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Nested\InputFieldInterpreter;

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
        return $this->mapNodeList(function ($fieldNode) {
            $fieldInterpreter = new InputFieldInterpreter($fieldNode);

            return $fieldInterpreter->generateType();
        }, $this->_astNode->fields);
	}

}