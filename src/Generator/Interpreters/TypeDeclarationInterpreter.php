<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\Types\Type;

class TypeDeclarationInterpreter extends Interpreter {
	/**
	 * @param ObjectTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return string|null
	 */
	public function getDescription() {
		return $this->_astNode->description;
	}

	public function getFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new FieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields);
	}

	/**
	 * @return Type
	 */
	public function generateType() {
		return new Type(
			$this->getName(),
			null,
			$this->getFields(),
			$this->getDescription()
		);
	}
}