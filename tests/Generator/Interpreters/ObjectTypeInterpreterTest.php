<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\ObjectTypeInterpreter;


class ObjectTypeInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode);
		$interpretedName = $interpreter->getName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode);
		$interpretedDescription = $interpreter->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}
}