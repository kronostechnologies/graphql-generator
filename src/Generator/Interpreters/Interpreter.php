<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\Node;
use GraphQLGen\Generator\Formatters\StubFormatter;

abstract class Interpreter {
	/**
	 * @var Node
	 */
	protected $_astNode;

	/**
	 * @param StubFormatter $formatter
	 * @return mixed
	 */
	public abstract function generateType($formatter);
}