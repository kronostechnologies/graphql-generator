<?php


namespace GraphQLGen\Tests\Generator;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\GeneratorFactory;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\InterfaceInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Generator\Interpreters\TypeDeclarationInterpreter;
use GraphQLGen\Generator\Types\InterfaceDeclaration;

class GeneratorFactoryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var GeneratorFactory
	 */
	protected $_factory;

	public function setUp() {
		$this->_factory = new GeneratorFactory();
	}

	public function test_GivenScalarTypeNode_getCorrectInterpreter_ShouldReturnScalarInterpreter() {
		$node = $this->GivenScalarTypeNode();

		$retVal = $this->_factory->getCorrectInterpreter($node);

		$this->assertInstanceOf(ScalarInterpreter::class, $retVal);
	}

	public function test_GivenEnumTypeNode_getCorrectInterpreter_ShouldReturnEnumInterpreter() {
		$node = $this->GivenEnumTypeNode();

		$retVal = $this->_factory->getCorrectInterpreter($node);

		$this->assertInstanceOf(EnumInterpreter::class, $retVal);
	}

	public function test_GivenObjectTypeNode_getCorrectInterpreter_ShouldReturnObjectInterpreter() {
		$node = $this->GivenObjectTypeNode();

		$retVal = $this->_factory->getCorrectInterpreter($node);

		$this->assertInstanceOf(TypeDeclarationInterpreter::class, $retVal);
	}

	public function test_GivenInterfaceTypeNode_getCorrectInterpreter_ShouldReturnInterfaceInterpreter() {
		$node = $this->GivenInterfaceNode();

		$retVal = $this->_factory->getCorrectInterpreter($node);

		$this->assertInstanceOf(InterfaceInterpreter::class, $retVal);
	}

	public function test_GivenUndefinedTypeNode_getCorrectInterpreter_ShouldReturnNull() {
		$node = $this->GivenUndefinedTypeNode();

		$retVal = $this->_factory->getCorrectInterpreter($node);

		$this->assertNull($retVal);
	}

	protected function GivenScalarTypeNode() {
		return new ScalarTypeDefinitionNode([]);
	}

	protected function GivenEnumTypeNode() {
		return new EnumTypeDefinitionNode([]);
	}

	protected function GivenObjectTypeNode() {
		return new ObjectTypeDefinitionNode([]);
	}

	protected function GivenInterfaceNode() {
		return new InterfaceTypeDefinitionNode([]);
	}

	protected function GivenUndefinedTypeNode() {
		return new NamedTypeNode([]);
	}
}