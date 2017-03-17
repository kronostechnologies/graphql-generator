<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Types\Scalar;

class ScalarInterpreter extends MainTypeInterpreter {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
	 */
	public function __construct(ScalarTypeDefinitionNode $astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return string|null
	 */
	public function interpretDescription() {
		return $this->_astNode->description;
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