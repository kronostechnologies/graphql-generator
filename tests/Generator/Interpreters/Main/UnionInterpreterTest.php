<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Main;


use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Generator\Interpreters\Main\UnionInterpreter;

// ToDo: Standardize
class UnionInterpreterTest extends \PHPUnit_Framework_TestCase {
	const UNION_DESC = 'A union description';
	const UNION_NAME = 'AUnion';

	public function test_GivenNodeWithName_interpretName_WillReturnName() {
		$node = $this->GivenNode();
		$this->GivenNodeWithName($node);
		$interpreter = $this->GivenUnionInterpreter($node);

		$retVal = $interpreter->interpretName();

		$this->assertEquals(self::UNION_NAME, $retVal);
	}

	public function test_GivenNodeWithDescription_interpretDescription_WillReturnDescription() {
		$node = $this->GivenNode();
		$this->GivenNodeWithDescription($node);
		$interpreter = $this->GivenUnionInterpreter($node);

		$retVal =$interpreter->interpretDescription();

		$this->assertEquals(self::UNION_DESC, $retVal);
	}

	public function test_GivenNodeWithNoTypes_interpretTypes_WillReturnEmptyArray() {
		$node = $this->GivenNode();
		$this->GivenNodeWithNoTypes($node);
		$interpreter = $this->GivenUnionInterpreter($node);

		$retVal = $interpreter->interpretTypes();

		$this->assertEmpty($retVal);
	}

	public function test_GivenNodeWithTypes_interpretTypes_WillReturnFilledArray() {
		$node = $this->GivenNode();
		$this->GivenNodeWithTypes($node);
		$interpreter = $this->GivenUnionInterpreter($node);

		$retVal = $interpreter->interpretTypes();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenNodeWithNoTypes_generateType_WillReturnUnionInstance() {
		$node = $this->GivenNode();
		$this->GivenNodeWithName($node);
		$this->GivenNodeWithNoTypes($node);
		$interpreter = $this->GivenUnionInterpreter($node);

		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(UnionInterpretedType::class, $retVal);
	}

	protected function GivenNode() {
		return new UnionTypeDefinitionNode([]);
	}

	protected function GivenNodeWithName(UnionTypeDefinitionNode $node) {
		$node->name = new NameNode([]);
		$node->name->value = self::UNION_NAME;
	}

	protected function GivenNodeWithDescription(UnionTypeDefinitionNode $node) {
		$node->description = self::UNION_DESC;
	}

	protected function GivenNodeWithNoTypes(UnionTypeDefinitionNode $node) {
		$node->types = [];
	}

	protected function GivenNodeWithTypes(UnionTypeDefinitionNode $node) {
		$type1 = new NamedTypeNode([]);
		$type1->name = new NameNode([]);
		$type1->type = new NamedTypeNode([]);
		$type1->type->name = new NameNode([]);

		$type2 = new NamedTypeNode([]);
		$type2->name = new NameNode([]);
		$type2->type = new NamedTypeNode([]);
		$type2->type->name = new NameNode([]);

		$node->types = [$type1, $type2];
	}

	protected function GivenUnionInterpreter(UnionTypeDefinitionNode $node) {
		return new UnionInterpreter($node);
	}
}