<?php


namespace GraphQLGen\Tests;


use GraphQL\SyntaxError;
use GraphQLGen\AstParser;
use PHPUnit\Framework\TestCase;

class AstParserTest extends TestCase
{
    const INVALID_SCHEMA_CONTENT = "asdasdm";
    const VALID_SCHEMA_CONTENT = "type Query { id: ID }";

    public function test_ValidFileContent_generateAst_Succeeds()
    {
        $astParser = new AstParser(self::VALID_SCHEMA_CONTENT);

        $astParser->generateAst();

        $this->assertTrue(true);
    }

    public function test_InvalidFileContent_generateAst_Fails()
    {
        $astParser = new AstParser(self::INVALID_SCHEMA_CONTENT);

        $this->expectException(SyntaxError::class);

        $astParser->generateAst();
    }
}