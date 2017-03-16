<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\Types\BaseTypeGenerator;

abstract class NestedTypeInterpreter extends Interpreter {
	/**
	 * @return BaseTypeGenerator
	 */
	public abstract function generateType();
}