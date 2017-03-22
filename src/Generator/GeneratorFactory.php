<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\DefinitionNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\Main\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\Main\InputInterpreter;
use GraphQLGen\Generator\Interpreters\Main\InterfaceInterpreter;
use GraphQLGen\Generator\Interpreters\Main\MainTypeInterpreter;
use GraphQLGen\Generator\Interpreters\Main\ScalarInterpreter;
use GraphQLGen\Generator\Interpreters\Main\TypeDeclarationInterpreter;
use GraphQLGen\Generator\Interpreters\Main\UnionInterpreter;

class GeneratorFactory {
	/**
	 * @param DefinitionNode|ScalarTypeDefinitionNode|EnumTypeDefinitionNode|ObjectTypeDefinitionNode|InterfaceTypeDefinitionNode|InputObjectTypeDefinitionNode|UnionTypeDefinitionNode $astDefinitionNode
	 * @return MainTypeInterpreter
	 */
	public function getCorrectInterpreter($astDefinitionNode) {
		switch($astDefinitionNode->kind) {
			case NodeKind::SCALAR_TYPE_DEFINITION:
				return new ScalarInterpreter($astDefinitionNode);
			case NodeKind::ENUM_TYPE_DEFINITION:
				return new EnumInterpreter($astDefinitionNode);
			case NodeKind::OBJECT_TYPE_DEFINITION:
				return new TypeDeclarationInterpreter($astDefinitionNode);
			case NodeKind::INTERFACE_TYPE_DEFINITION:
				return new InterfaceInterpreter($astDefinitionNode);
			case NodeKind::INPUT_OBJECT_TYPE_DEFINITION:
				return new InputInterpreter($astDefinitionNode);
			case NodeKind::UNION_TYPE_DEFINITION:
				return new UnionInterpreter($astDefinitionNode);
		}

		return null;
	}

}