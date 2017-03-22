<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQLGen\Generator\Interpreters\Nested\FieldArgumentInterpreter;

class FieldArgumentInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'argument';
	const VALID_DESCRIPTION = 'desc';
	const VALID_DEFAULT_VALUE = '111';
	const VALID_TYPE_NAME = 'TYPENAME';

	public function test_GivenValidName_getName_WillReturnValidName() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenValidName($node);

		$interpreter = new FieldArgumentInterpreter($node);
		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenValidDescription_getDescription_WillReturnValidDescription() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenValidDescription($node);

		$interpreter = new FieldArgumentInterpreter($node);
		$retVal = $interpreter->interpretDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $retVal);
	}

	public function test_GivenNoDefaultValue_getDefaultValue_WillReturnNull() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenValidName($node);

		$interpreter =  new FieldArgumentInterpreter($node);
		$retVal = $interpreter->interpretDefaultValue();

		$this->assertNull($retVal);
	}

	public function test_GivenDefaultValue_getDefaultValue_WillReturnDefaultValue() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenDefaultValue($node);

		$interpreter = new FieldArgumentInterpreter($node);
		$retVal = $interpreter->interpretDefaultValue();

		$this->assertEquals(self::VALID_DEFAULT_VALUE, $retVal);
	}

	public function test_GivenValidType_getType_WillReturnValidTypeName() {
		$node = new InputValueDefinitionNode([]);
		$this->GivenValidType($node);

		$interpreter = new FieldArgumentInterpreter($node);
		$retVal = $interpreter->interpretType();

		$this->assertEquals(self::VALID_TYPE_NAME, $retVal->getTypeName());
	}

	protected function GivenValidName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}

	protected function GivenValidDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	/**
	 * @param $node
	 */
	protected function GivenDefaultValue($node) {
		$node->defaultValue = new InputValueDefinitionNode([]);
		$node->defaultValue = new StringValueNode([]);
		$node->defaultValue->value = self::VALID_DEFAULT_VALUE;
	}

	protected function GivenValidType($node) {
		$node->type = new NamedTypeNode([]);
		$node->type->name = new NameNode([]);
		$node->type->name->value = self::VALID_TYPE_NAME;
	}
}