<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\EnumType;
use GraphQLGen\Generator\Types\EnumTypeValue;
use GraphQLGen\Generator\Types\GeneratorTypeInterface;

class EnumInterpreter implements GeneratorInterpreterInterface {

    /**
     * @var EnumTypeDefinitionNode
     */
    protected $_astNode;

    /**
     * @param EnumTypeDefinitionNode $astNode
     */
    public function __construct($astNode) {
        $this->_astNode = $astNode;
    }

    /**
     * @param StubFormatter $formatter
     * @return GeneratorTypeInterface
     */
    public function getGeneratorType($formatter)
    {
        return new EnumType(
            $this->_astNode->name->value,
            $this->getEnumValues(),
            $formatter,
            $this->_astNode->description
        );
    }

    /**
     * @return EnumTypeValue[]
     */
    protected function getEnumValues() {
        $enumValues = [];

        foreach ($this->_astNode->values as $possibleValue) {
            $enumValues[] = new EnumTypeValue(
                $possibleValue->name,
                $possibleValue->description
            );
        }

        return $enumValues;
    }
}