<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NamedType;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Nested\InputFieldInterpreter;

class InputFieldInterpreterTest extends \PHPUnit_Framework_TestCase {
	const INPUT_FIELD_DESC = 'Description of an input field';
	const INPUT_FIELD_NAME = 'AnInputField';
	const INPUT_FIELD_TYPE_NAME = 'InputFieldType';

	public function test_GivenNodeWithName_interpretName_WillReturnName() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenNodeWithName($node);

		$interpreter = new InputFieldInterpreter($node);
		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::INPUT_FIELD_NAME, $retVal);
	}

	public function test_GivenNodeWithDescription_interpretDescription_WillReturnDescription() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenNodeWithDescription($node);

		$interpreter = new InputFieldInterpreter($node);
		$retVal = $interpreter->interpretDescription();

		$this->assertEquals(self::INPUT_FIELD_DESC, $retVal);
	}

	public function test_GivenNodeWithType_interpretType_WillReturnRightType() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenNodeWithType($node);

		$interpreter = new InputFieldInterpreter($node);
		$retVal = $interpreter->interpretType();

		$this->assertEquals(self::INPUT_FIELD_TYPE_NAME, $retVal->getTypeName());
	}

	public function test_GivenValidNode_generateType_WillReturnRightType() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenNodeWithName($node);
		$this->GivenNodeWithType($node);

		$interpreter = new InputFieldInterpreter($node);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(InputFieldInterpretedType::class, $retVal);
	}

	private function GivenNodeWithDescription($node) {
		$node->description = self::INPUT_FIELD_DESC;
	}

	private function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::INPUT_FIELD_NAME;
	}

	private function GivenNodeWithType($node) {
		$node->type = new NamedType([]);
		$node->type->name = new NameNode([]);
		$node->type->name->value = self::INPUT_FIELD_TYPE_NAME;
	}
}