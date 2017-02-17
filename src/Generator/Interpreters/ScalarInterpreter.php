<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\ScalarType;

class ScalarInterpreter implements GeneratorInterpreterInterface {
    /**
     * @var ScalarTypeDefinitionNode
     */
    protected $_astNode;

    /**
     * @param ScalarTypeDefinitionNode $astNode
     */
    public function __construct($astNode) {
        $this->_astNode = $astNode;
    }

    /**
     * @param StubFormatter $formatter
     * @return ScalarType
     */
    public function getGeneratorType($formatter) {
        return new ScalarType(
            $this->getName(),
            $formatter,
            $this->getDescription()
        );
    }

    /**
     * @return string
     */
    protected function getName() {
        return $this->_astNode->name->value;
    }

    /**
     * @return string
     */
    protected function getDescription() {
        return $this->_astNode->description;
    }
}