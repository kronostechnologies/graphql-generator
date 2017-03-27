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
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
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

	/**
	 * @param StubFormatter $formatter
	 * @param mixed $type
	 * @return FragmentGeneratorInterface
	 */
	public function createFragmentGenerator($formatter, $type) {
		switch(get_class($type)) {
			case EnumInterpretedType::class:
				return $this->createEnumGenerator($formatter, $type);
			case InputInterpretedType::class:
				return $this->createInputGenerator($formatter, $type);
			case InterfaceDeclarationInterpretedType::class:
				return $this->createInterfaceGenerator($formatter, $type);
			case ScalarInterpretedType::class:
				return $this->createScalarGenerator($formatter, $type);
			case TypeDeclarationInterpretedType::class:
				return $this->createTypeDeclarationGenerator($formatter, $type);
			case UnionInterpretedType::class:
				return $this->createUnionGenerator($formatter, $type);
		}
	}

	private function createEnumGenerator($formatter, $type) {
		$generator = new EnumFragmentGenerator();
		$generator->setEnumType($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	private function createInputGenerator($formatter, $type) {
		$generator = new InputFragmentGenerator();
		$generator->setInputType($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	private function createInterfaceGenerator($formatter, $type) {
		$generator = new InterfaceFragmentGenerator();
		$generator->setInterfaceType($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	private function createScalarGenerator($formatter, $type) {
		$generator = new ScalarFragmentGenerator();
		$generator->setScalarType($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	private function createTypeDeclarationGenerator($formatter, $type) {
		$generator = new TypeDeclarationFragmentGenerator();
		$generator->setTypeDeclaration($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	private function createUnionGenerator($formatter, $type) {
		$generator = new UnionFragmentGenerator();
		$generator->setUnionType($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

}