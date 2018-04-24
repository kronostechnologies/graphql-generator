<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Interpreters\Nested\FieldInterpreter;

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

	    $interpretedTypes = [];
	    foreach($this->_astNode->fields as $fieldNode){
            $fieldInterpreter = new FieldInterpreter($fieldNode);

            $interpretedTypes[] = $fieldInterpreter->generateType();
        }

        return $interpretedTypes;
	}
}