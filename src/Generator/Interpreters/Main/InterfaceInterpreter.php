<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\Interpreters\Nested\FieldInterpreter;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\SubTypes\Field;

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
	 * @return Field[]
	 */
	public function interpretFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new FieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields);
	}
}