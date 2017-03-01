<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

abstract class MainTypeInterpreter extends Interpreter {
	/**
	 * @param StubFormatter $formatter
	 * @return BaseTypeGeneratorInterface
	 */
	public abstract function generateType($formatter);
}