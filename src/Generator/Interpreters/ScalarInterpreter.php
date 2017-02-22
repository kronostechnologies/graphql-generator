<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Types\Scalar;

class ScalarInterpreter extends Interpreter {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
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

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Scalar
	 */
	public function generateType($formatter) {
		return new Scalar(
			$this->getName(),
			$formatter,
			$this->getDescription()
		);
	}
}