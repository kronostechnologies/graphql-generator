<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\TypeDeclarationInterpreter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\Type;


class TypeDeclarationInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';

	protected $_interpreter;

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$interpretedName = $interpreter->getName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$interpretedDescription = $interpreter->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenOneField_getFields_ReturnsFieldArray() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$retVal = $interpreter->getFields();

		$this->assertCount(1, $retVal);
		$this->assertInstanceOf(Field::class, array_shift($retVal));
	}

	public function test_GivenNodeWithName_generateType_WillReturnRightType() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$retVal = $interpreter->generateType(null);

		$this->assertInstanceOf(Type::class, $retVal);
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	/**
	 * @param $node
	 */
	protected function GivenNodeWithField($node) {
		// ToDo: Mock away from here
		$node->fields = [];

		$dummyFieldDefinition = new FieldDefinitionNode([]);
		$dummyFieldDefinition->name = new NameNode([]);
		$dummyFieldDefinition->type = new NamedTypeNode([]);
		$dummyFieldDefinition->type->name = new NameNode([]);

		$dummyDescriptionNode = new InputValueDefinitionNode([]);
		$dummyDescriptionNode->name = new NameNode([]);
		$dummyDescriptionNode->defaultValue = new IntValueNode([]);
		$dummyDescriptionNode->type = new NamedTypeNode([]);
		$dummyDescriptionNode->type->name = new NameNode([]);

		$dummyFieldDefinition->arguments = [$dummyDescriptionNode];

		$node->fields[] = $dummyFieldDefinition;
	}
}