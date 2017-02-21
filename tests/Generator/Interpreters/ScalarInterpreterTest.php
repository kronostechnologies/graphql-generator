<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\ScalarTypeInterpreter;

class ScalarInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$scalarNode = new ScalarTypeDefinitionNode([]);
		$this->GivenNodeWithName($scalarNode);

		$interpreter = new ScalarTypeInterpreter($scalarNode);
		$interpretedName = $interpreter->getName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$scalarNode = new ScalarTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($scalarNode);

		$interpreter = new ScalarTypeInterpreter($scalarNode);
		$interpretedDescription = $interpreter->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}
}