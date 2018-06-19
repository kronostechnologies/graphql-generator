<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQLGen\Old\Generator\Interpreters\Interpreter;

abstract class MainTypeInterpreter extends Interpreter {
	public abstract function generateType();
}