<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\Node;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGenerator;

abstract class Interpreter {
	/**
	 * @var Node
	 */
	protected $_astNode;

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
}