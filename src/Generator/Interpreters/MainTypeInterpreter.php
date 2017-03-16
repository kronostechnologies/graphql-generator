<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGenerator;

abstract class MainTypeInterpreter extends Interpreter {
	/**
	 * @param StubFormatter $formatter
	 * @return BaseTypeGenerator
	 */
	public abstract function generateType($formatter);
}