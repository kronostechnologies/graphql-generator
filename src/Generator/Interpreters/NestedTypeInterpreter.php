<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

abstract class NestedTypeInterpreter extends Interpreter {
	/**
	 * @return BaseTypeGeneratorInterface
	 */
	public abstract function generateType();
}