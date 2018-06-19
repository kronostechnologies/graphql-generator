<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Main;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Main\TypeDeclarationInterpreter;


class TypeDeclarationInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';
	const INTERFACE_NODE = 'InterfaceNode';

	protected $_interpreter;

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$interpretedName = $interpreter->interpretName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$interpretedDescription = $interpreter->interpretDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenOneField_getFields_ReturnsFieldArray() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$retVal = $interpreter->interpretFields();

		$this->assertCount(1, $retVal);
		$this->assertInstanceOf(FieldInterpretedType::class, array_shift($retVal));
	}

	public function test_GivenNodeWithName_generateType_WillReturnRightType() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(TypeDeclarationInterpretedType::class, $retVal);
	}

	public function test_GivenNodeWithInterface_generateType_WillContainInterfaceAsDependency() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);
		$this->GivenNodeWithInterface($objectTypeNode);

		$interpreter = new TypeDeclarationInterpreter($objectTypeNode);
		$retVal = $interpreter->generateType();

		$this->assertContains(self::INTERFACE_NODE, $retVal->getInterfacesNames());
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	protected function GivenNodeWithInterface($node) {
		$interfaceNode = new NamedTypeNode([]);
		$interfaceNode->name = new NameNode([]);
		$interfaceNode->name->value = self::INTERFACE_NODE;

		$node->interfaces = new NodeList([$interfaceNode]);
	}

	/**
	 * @param $node
	 */
	protected function GivenNodeWithField($node) {


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

        $node->fields = new NodeList([$dummyFieldDefinition]);
	}
}