<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\GeneratorFactory;
use GraphQLGen\Generator\Interpreters\ObjectTypeInterpreter;


class ObjectTypeInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';

	/**
	 * @var GeneratorFactory|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_factory;
	protected $_interpreter;

	public function setUp() {
		$this->_factory = $this->createMock(GeneratorFactory::class);
	}

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithName($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode, $this->_factory);
		$interpretedName = $interpreter->getName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode, $this->_factory);
		$interpretedDescription = $interpreter->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenOneField_getFields_ExpectsFactoryToCreateFieldInterpreter() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode, $this->_factory);
		$this->_factory->expects($this->once())->method('createFieldTypeInterpreter');

		$interpreter->getFields();
	}

	public function test_GivenOneField_getFields_ExpectsFactoryToCreateTypeGenerator() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode, $this->_factory);
		$this->_factory->expects($this->once())->method('createFieldTypeGeneratorType');

		$interpreter->getFields();
	}

	public function test_GivenOneField_getFields_ReturnsSingleEntry() {
		$objectTypeNode = new ObjectTypeDefinitionNode([]);
		$this->GivenNodeWithField($objectTypeNode);

		$interpreter = new ObjectTypeInterpreter($objectTypeNode, $this->_factory);
		$retVal = $interpreter->getFields();

		$this->assertCount(1, $retVal);
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	protected function GivenNodeWithField($node) {
		$node->fields = [];
		$node->fields[] = new FieldDefinitionNode([]);
	}
}