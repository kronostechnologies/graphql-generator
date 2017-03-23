<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\Types\Scalar;

class ScalarInterpreter extends MainTypeInterpreter {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return ScalarInterpretedType
	 */
	public function generateType() {
		$interpretedType = new ScalarInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());

		return $interpretedType;
	}
}