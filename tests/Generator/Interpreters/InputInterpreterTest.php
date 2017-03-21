<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Interpreters\InputInterpreter;
use GraphQLGen\Generator\Types\Input;

class InputInterpreterTest extends \PHPUnit_Framework_TestCase {
	const TYPE_DESC = 'A Description';
	const TYPE_NAME = 'AType';


	public function test_GivenTypeWithName_interpretName_WillReturnName() {
		$type = $this->GivenType();
		$this->GivenTypeWithName($type);
		$interpreter = $this->GivenInterpreter($type);

		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::TYPE_NAME, $retVal);
	}

	public function test_GivenTypeWithDescription_interpretDescription_WillReturnDescription() {
		$type = $this->GivenType();
		$this->GivenTypeWithDescription($type);
		$interpreter = $this->GivenInterpreter($type);

		$retVal = $interpreter->interpretDescription();

		$this->assertEquals(self::TYPE_DESC, $retVal);
	}

	public function test_GivenTypeWithNoFields_interpretFields_WillReturnEmptyArray() {
		$type = $this->GivenType();
		$this->GivenTypeWithNoFields($type);
		$interpreter = $this->GivenInterpreter($type);

		$retVal = $interpreter->interpretFields();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeWithFields_interpretFields_WillReturnFields() {
		$type = $this->GivenType();
		$this->GivenTypeWithFields($type);
		$interpreter = $this->GivenInterpreter($type);

		$retVal = $interpreter->interpretFields();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenTypeWithNameAndNoFields_generateType_WillReturnInputType() {
		$type = $this->GivenType();
		$this->GivenTypeWithName($type);
		$this->GivenTypeWithNoFields($type);
		$interpreter = $this->GivenInterpreter($type);

		$retVal = $interpreter->generateType(new StubFormatter());

		$this->assertInstanceOf(Input::class, $retVal);
	}

	protected function GivenType() {
		return new InputObjectTypeDefinitionNode([]);
	}

	protected function GivenTypeWithName(InputObjectTypeDefinitionNode $node) {
		$node->name = new NameNode([]);
		$node->name->value = self::TYPE_NAME;
	}

	protected function GivenTypeWithDescription(InputObjectTypeDefinitionNode $node) {
		$node->description = self::TYPE_DESC;
	}

	protected function GivenTypeWithNoFields(InputObjectTypeDefinitionNode $node) {
		$node->fields = [];
	}

	protected function GivenTypeWithFields(InputObjectTypeDefinitionNode $node) {
		$field1 = new InputValueDefinitionNode([]);
		$field1->name = new NameNode([]);
		$field1->type = new NamedTypeNode([]);
		$field1->type->name = new NameNode([]);

		$field2 = new InputValueDefinitionNode([]);
		$field2->name = new NameNode([]);
		$field2->type = new NamedTypeNode([]);
		$field2->type->name = new NameNode([]);

		$node->fields = [$field1, $field2];
	}

	protected function GivenInterpreter(InputObjectTypeDefinitionNode $node) {
		return new InputInterpreter($node);
	}
}