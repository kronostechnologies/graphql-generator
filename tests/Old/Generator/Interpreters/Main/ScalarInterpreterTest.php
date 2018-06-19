<?php


namespace GraphQLGen\Tests\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Main\ScalarInterpreter;

class ScalarInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$scalarNode = new ScalarTypeDefinitionNode([]);
		$this->GivenNodeWithName($scalarNode);

		$interpreter = new ScalarInterpreter($scalarNode);
		$interpretedName = $interpreter->interpretName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$scalarNode = new ScalarTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($scalarNode);

		$interpreter = new ScalarInterpreter($scalarNode);
		$interpretedDescription = $interpreter->interpretDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenNodeWithInformation_generateType_WillReturnRightType() {
		$scalarNode = new ScalarTypeDefinitionNode([]);
		$this->GivenNodeWithName($scalarNode);

		$interpreter = new ScalarInterpreter($scalarNode);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(ScalarInterpretedType::class, $retVal);
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}
}