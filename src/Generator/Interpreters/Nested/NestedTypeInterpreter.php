<?php


namespace GraphQLGen\Generator\Interpreters\Nested;


use GraphQLGen\Generator\Interpreters\Interpreter;

abstract class NestedTypeInterpreter extends Interpreter {
	public abstract function generateType();
}