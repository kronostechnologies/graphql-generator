<?php


namespace GraphQLGen;


use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;

class AstParser
{
    /**
     * @var DocumentNode|null
     */
    protected $ast;

    /**
     * @var string
     */
    protected $schemaFileContent;

    /**
     * @param string $schemaFileContent
     */
    public function __construct($schemaFileContent)
    {
        $this->schemaFileContent = $schemaFileContent;
    }

    public function generateAst()
    {
        $this->ast = Parser::parse($this->schemaFileContent);
    }

    public function getClassesDefinitions()
    {

    }

}