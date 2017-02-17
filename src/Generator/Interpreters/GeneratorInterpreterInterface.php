<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\GeneratorTypeInterface;

interface GeneratorInterpreterInterface {
    /**
     * @param StubFormatter $formatter
     * @return GeneratorTypeInterface
     */
    public function getGeneratorType($formatter);
}