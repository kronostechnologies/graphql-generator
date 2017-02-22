<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\Node;

abstract class Interpreter {
	/**
	 * @var Node
	 */
	protected $_astNode;

	public abstract function generateType();
}