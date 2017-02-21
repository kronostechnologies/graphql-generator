<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQLGen\Generator\Interpreters\FieldTypeInterpreter;

class FieldTypeInterpreterTest extends \PHPUnit_Framework_TestCase {
	const NODE_NAME = 'TARGET_NAME';

	public function test_GivenNamedTypeNode_isInList_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNamedTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isInList();

		$this->assertFalse($retVal);
	}

	public function test_GivenNamedTypeNode_isNullableList_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNamedTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableList();

		$this->assertFalse($retVal);
	}

	public function test_GivenNamedTypeNode_isNullableObject_ReturnsTrue() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNamedTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableObject();

		$this->assertTrue($retVal);
	}

	public function test_GivenNamedTypeNode_isNullableObject_ReturnsRightName() {
		$node = new FieldDefinitionNode([]);
		$this->GivenNamedTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->getName();

		$this->assertEquals(self::NODE_NAME, $retVal);
	}

	protected function GivenNamedTypeNode($node) {
		$node->type = new NamedTypeNode([]);
		$node->type->name = new NameNode([]);
		$node->type->name->value = self::NODE_NAME;
	}


	public function test_GivenListlessNonNullableTypeNode_isInList_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenListlessNonNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isInList();

		$this->assertFalse($retVal);
	}

	public function test_GivenListlessNonNullableTypeNode_isNullableList_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenListlessNonNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableList();

		$this->assertFalse($retVal);
	}

	public function test_GivenListlessNonNullableTypeNode_isNullableObject_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenListlessNonNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableObject();

		$this->assertFalse($retVal);
	}

	public function test_GivenListlessNonNullableTypeNode_isNullableObject_ReturnsRightName() {
		$node = new FieldDefinitionNode([]);
		$this->GivenListlessNonNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->getName();

		$this->assertEquals(self::NODE_NAME, $retVal);
	}

	protected function GivenListlessNonNullableTypeNode($node) {
		$node->type = new NonNullTypeNode([]);
		$node->type->type = new NamedTypeNode([]);
		$node->type->type->name = new NameNode([]);
		$node->type->type->name->value = self::NODE_NAME;
	}


	public function test_GivenWithListAllNullableTypeNode_isInList_ReturnsTrue() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithListAllNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isInList();

		$this->assertTrue($retVal);
	}

	public function test_GivenWithListAllNullableTypeNode_isNullableList_ReturnsTrue() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithListAllNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableList();

		$this->assertTrue($retVal);
	}

	public function test_GivenWithListAllNullableTypeNode_isNullableObject_ReturnsTrue() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithListAllNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableObject();

		$this->assertTrue($retVal);
	}

	public function test_GivenWithListAllNullableTypeNode_isNullableObject_ReturnsRightName() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithListAllNullableTypeNode($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->getName();

		$this->assertEquals(self::NODE_NAME, $retVal);
	}

	protected function GivenWithListAllNullableTypeNode($node) {
		$node->type = new ListTypeNode([]);
		$node->type->type = new NamedTypeNode([]);
		$node->type->type->name = new NameNode([]);
		$node->type->type->name->value = self::NODE_NAME;
	}


	public function test_GivenWithNonNullableListAndNonNullableObject_isInList_ReturnsTrue() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithNonNullableListAndNonNullableObject($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isInList();

		$this->assertTrue($retVal);
	}

	public function test_GivenWithNonNullableListAndNonNullableObject_isNullableList_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithNonNullableListAndNonNullableObject($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableList();

		$this->assertFalse($retVal);
	}

	public function test_GivenWithNonNullableListAndNonNullableObject_isNullableObject_ReturnsFalse() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithNonNullableListAndNonNullableObject($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->isNullableObject();

		$this->assertFalse($retVal);
	}

	public function test_GivenWithNonNullableListAndNonNullableObject_isNullableObject_ReturnsRightName() {
		$node = new FieldDefinitionNode([]);
		$this->GivenWithNonNullableListAndNonNullableObject($node);

		$interpreter = new FieldTypeInterpreter($node->type);
		$retVal = $interpreter->getName();

		$this->assertEquals(self::NODE_NAME, $retVal);
	}

	protected function GivenWithNonNullableListAndNonNullableObject($node) {
		$node->type = new NonNullTypeNode([]);
		$node->type->type = new ListTypeNode([]);
		$node->type->type->type = new NonNullTypeNode([]);
		$node->type->type->type->type = new NamedTypeNode([]);
		$node->type->type->type->type->name = new NameNode([]);
		$node->type->type->type->type->name->value = self::NODE_NAME;
	}
}