<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;

class ScalarInterpreter extends MainTypeInterpreter {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return ScalarInterpretedType
	 */
	public function generateType() {
		$interpretedType = new ScalarInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());

		return $interpretedType;
	}
}