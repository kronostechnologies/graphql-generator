<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQLGen\Generator\Interpreters\Interpreter;

abstract class MainTypeInterpreter extends Interpreter {
	public abstract function generateType();
}