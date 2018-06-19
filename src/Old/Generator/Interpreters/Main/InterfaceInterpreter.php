<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Nested\FieldInterpreter;

class InterfaceInterpreter extends MainTypeInterpreter {
	/**
	 * @param InterfaceTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return InterfaceDeclarationInterpretedType
	 */
	public function generateType() {
		$interpretedType = new InterfaceDeclarationInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setFields($this->interpretFields());

		return $interpretedType;
	}

	/**
	 * @return FieldInterpretedType[]
	 */
	public function interpretFields() {
        return $this->mapNodeList(function ($fieldNode) {
            $fieldInterpreter = new FieldInterpreter($fieldNode);

            return $fieldInterpreter->generateType();
        }, $this->_astNode->fields);
	}
}