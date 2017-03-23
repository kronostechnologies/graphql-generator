<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Interpreters\Interpreter;
use GraphQLGen\Generator\Types\BaseTypeGenerator;

abstract class MainTypeInterpreter extends Interpreter {
	/**
	 * @return BaseTypeGenerator
	 */
	public abstract function generateType();
}