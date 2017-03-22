<?php


namespace GraphQLGen\Generator\Interpreters\Nested;


use GraphQLGen\Generator\Interpreters\Interpreter;
use GraphQLGen\Generator\Types\BaseTypeGenerator;

abstract class NestedTypeInterpreter extends Interpreter {
	/**
	 * @return BaseTypeGenerator
	 */
	public abstract function generateType();
}