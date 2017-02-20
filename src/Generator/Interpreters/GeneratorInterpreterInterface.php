<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

interface GeneratorInterpreterInterface {
	/**
	 * @param StubFormatter $formatter
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType($formatter);
}