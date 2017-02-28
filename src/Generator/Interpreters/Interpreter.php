<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\Node;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

abstract class Interpreter {
	/**
	 * @var Node
	 */
	protected $_astNode;

	/**
	 * @param StubFormatter $formatter
	 * @return BaseTypeGeneratorInterface
	 */
	public abstract function generateType($formatter);
}