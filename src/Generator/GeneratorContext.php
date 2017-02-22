<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\DocumentNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;

class GeneratorContext {
	/**
	 * @var string
	 */
	public $namespace;

	/**
	 * @var DocumentNode
	 */
	public $ast;

	/**
	 * @var GeneratorWriterInterface
	 */
	public $writer;

	/**
	 * @var StubFormatter
	 */
	public $formatter;
}