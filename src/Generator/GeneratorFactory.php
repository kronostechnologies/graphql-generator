<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\ObjectTypeInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;

class GeneratorFactory {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
	 * @return ScalarInterpreter
	 */
	public function createScalarTypeInterpreter($astNode) {
		return new ScalarInterpreter($astNode);
	}

	/**
	 * @param EnumTypeDefinitionNode $astNode
	 * @return EnumInterpreter
	 */
	public function createEnumTypeInterpreter($astNode) {
		return new EnumInterpreter($astNode);
	}

	public function createObjectTypeInterpreter($astNode) {
		return new ObjectTypeInterpreter($astNode);
	}
}