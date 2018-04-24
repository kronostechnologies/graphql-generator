<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Interpreters\Nested\FieldInterpreter;

class TypeDeclarationInterpreter extends MainTypeInterpreter {
	/**
	 * @param ObjectTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return TypeDeclarationInterpretedType
	 */
	public function generateType() {
		$interpretedType = new TypeDeclarationInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setFields($this->interpretFields());
		$interpretedType->setInterfacesNames($this->interpretInterfacesNames());

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

	/**
	 * @return string[]
	 */
	public function interpretInterfacesNames() {
	    return $this->mapNodeList(function (NamedTypeNode $interfaceNameNode) {
            return $interfaceNameNode->name->value;
        }, $this->_astNode->interfaces);
    }
}