<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NamedType;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQLGen\Generator\Interpreters\FieldInterpreter;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_FIELD_NAME = 'MyField';
	const VALID_FIELD_DESC = 'A description';
	const VALID_FIELD_TYPE_NAME = 'MyFieldType';
	const VALID_ARG_NODE_NAME = 'arg1';
	const VALID_ARG_NODE_VALUE = 'ArgumetnValueString';

	public function test_GivenNodeWithName_getName_WillReturnName() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithName($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::VALID_FIELD_NAME, $retVal);
	}

	public function test_GivenNodeWithDescription_getDescription_WillReturnDescription() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithDescription($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretDescription();

		$this->assertEquals(self::VALID_FIELD_DESC, $retVal);
	}

	public function test_GivenNodeWithType_getType_WillReturnRightType() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithType($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretType();

		$this->assertEquals(self::VALID_FIELD_TYPE_NAME, $retVal->getTypeName());
	}

	public function test_GivenNodeWithOneArgument_getArguments_WillReturnInstanceOfFieldArgument() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithArgumentsDeclared($node);
		$this->GivenNodeWithAdditionalArgument($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretArguments();

		$this->assertInstanceOf(FieldArgument::class, $retVal[0]);
	}

	public function test_GivenNodeWithOneArgument_getArguments_WillReturnOneItem() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithArgumentsDeclared($node);
		$this->GivenNodeWithAdditionalArgument($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretArguments();

		$this->assertCount(1, $retVal);
	}

	public function test_GivenNodeWithMultipleArguments_getArguments_WillReturnMultipleArguments() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNodeWithArgumentsDeclared($node);
		$this->GivenNodeWithAdditionalArgument($node);
		$this->GivenNodeWithAdditionalArgument($node);
		$this->GivenNodeWithAdditionalArgument($node);

		$interpreter = new FieldInterpreter($node);
		$retVal = $interpreter->interpretArguments();

		$this->assertCount(3, $retVal);
	}

	/**
	 * @param $node
	 */
	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_FIELD_NAME;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_FIELD_DESC;
	}

	protected function GivenNodeWithType($node) {
		$node->type = new NamedType([]);
		$node->type->name = new NameNode([]);
		$node->type->name->value = self::VALID_FIELD_TYPE_NAME;
	}

	protected function GivenNodeWithArgumentsDeclared($node) {
		$node->arguments = [];
	}

	protected function GivenNodeWithAdditionalArgument($node) {
		$argNode = new InputValueDefinitionNode([]);
		$argNode->name = new NameNode([]);
		$argNode->name->value = self::VALID_ARG_NODE_NAME;
		$argNode->defaultValue = new StringValueNode([]);
		$argNode->defaultValue->value = self::VALID_ARG_NODE_VALUE;
		$argNode->type = new NamedType([]);
		$argNode->type->name = new NameNode([]);

		$node->arguments[] = $argNode;
	}

}