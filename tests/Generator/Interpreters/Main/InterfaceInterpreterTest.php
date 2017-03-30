<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Main;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Interpreters\Main\InterfaceInterpreter;

class InterfaceInterpreterTest extends \PHPUnit_Framework_TestCase {
	const INTERFACE_DESCRIPTION = 'Description of this interface';
	const INTERFACE_NAME = 'AnInterface';

	public function test_GivenNodeWithName_interpretName_ReturnsCorrectName() {
		$node = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithName($node);

		$interpreter = new InterfaceInterpreter($node);
		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::INTERFACE_NAME, $retVal);
	}

	public function test_GivenNodeWithDescription_interpretDescription_ReturnsDescription() {
		$node = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($node);

		$interpreter = new InterfaceInterpreter($node);
		$retVal = $interpreter->interpretDescription();

		$this->assertEquals(self::INTERFACE_DESCRIPTION, $retVal);
	}

	public function test_GivenOneField_interpretFields_ReturnsFieldArray() {
		$node = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithOneField($node);

		$interpreter = new InterfaceInterpreter($node);
		$retVal = $interpreter->interpretFields();

		$this->assertCount(1, $retVal);
		$this->assertInstanceOf(FieldInterpretedType::class, array_shift($retVal));
	}

	public function test_GivenValidNode_generateType_WillReturnRightType() {
		$node = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithName($node);

		$interpreter = new InterfaceInterpreter($node);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(InterfaceDeclarationInterpretedType::class, $retVal);
	}

	public function test_GivenNodeWithInformation_generateType_WillReturnRightType() {
		$interfaceNode = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithName($interfaceNode);

		$interpreter = new InterfaceInterpreter($interfaceNode);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(InterfaceDeclarationInterpretedType::class, $retVal);
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::INTERFACE_DESCRIPTION;
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::INTERFACE_NAME;
	}

	protected function GivenNodeWithOneField($node) {
		$node->fields = [];

		$dummyFieldDefinition = new FieldDefinitionNode([]);
		$dummyFieldDefinition->name = new NameNode([]);
		$dummyFieldDefinition->type = new NamedTypeNode([]);
		$dummyFieldDefinition->type->name = new NameNode([]);

		$dummyFieldDefinition->arguments = [];

		$node->fields[] = $dummyFieldDefinition;
	}
}