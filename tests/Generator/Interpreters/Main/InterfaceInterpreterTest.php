<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Main;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\Interpreters\Main\InterfaceInterpreter;

class InterfaceInterpreterTest extends \PHPUnit_Framework_TestCase {
	const INTERFACE_NAME = 'AnInterface';

	public function test_GivenNodeWithInformation_generateType_WillReturnRightType() {
		$interfaceNode = new InterfaceTypeDefinitionNode([]);
		$this->GivenNodeWithName($interfaceNode);

		$interpreter = new InterfaceInterpreter($interfaceNode);
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(InterfaceDeclarationInterpretedType::class, $retVal);
	}

	private function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::INTERFACE_NAME;
	}

	// ToDo: Additional unit tests
}