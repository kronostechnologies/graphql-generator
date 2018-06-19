<?php


namespace GraphQLGen\Old\Generator\Interpreters\Nested;


use GraphQLGen\Old\Generator\Interpreters\Interpreter;

abstract class NestedTypeInterpreter extends Interpreter {
	public abstract function generateType();
}