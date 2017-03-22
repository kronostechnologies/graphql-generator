<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
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
	 * @return Scalar
	 */
	public function generateType($formatter) {
		return new Scalar(
			$this->interpretName(),
			$formatter,
			$this->interpretDescription()
		);
	}
}