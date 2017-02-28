<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\DocumentNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;
use GraphQLGen\Generator\Writer\WriterContext;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorContext {

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

	/**
	 * @var WriterContext
	 */
	public $writerContext;
}