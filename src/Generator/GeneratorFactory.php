<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\EnumTypeInterpreter;
use GraphQLGen\Generator\Interpreters\FieldTypeInterpreter;
use GraphQLGen\Generator\Interpreters\ObjectTypeInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarTypeInterpreter;
use GraphQLGen\Generator\Types\EnumType;
use GraphQLGen\Generator\Types\ObjectType;
use GraphQLGen\Generator\Types\ScalarType;
use GraphQLGen\Generator\Types\SubTypes\FieldType;

class GeneratorFactory {
	/**
	 * @param ScalarTypeDefinitionNode $astNode
	 * @return ScalarTypeInterpreter
	 */
	public function createScalarTypeInterpreter($astNode) {
		return new ScalarTypeInterpreter($astNode);
	}

	/**
	 * @param EnumTypeDefinitionNode $astNode
	 * @return EnumTypeInterpreter
	 */
	public function createEnumTypeInterpreter($astNode) {
		return new EnumTypeInterpreter($astNode);
	}

	/**
	 * @param $astNode
	 * @return ObjectTypeInterpreter
	 */
	public function createObjectTypeInterpreter($astNode) {
		return new ObjectTypeInterpreter($astNode);
	}

	/**
	 * @param EnumTypeInterpreter $interpreter
	 * @param StubFormatter $formatter
	 * @return EnumType
	 */
	public function createEnumGeneratorType($interpreter, $formatter) {
		return new EnumType(
			$interpreter->getName(),
			$interpreter->getEnumValues(),
			$formatter,
			$interpreter->getDescription()
		);
	}

	/**
	 * @param FieldTypeInterpreter $interpreter
	 * @return FieldType
	 */
	public function createFieldTypeGeneratorType($interpreter) {
		return new FieldType(
			$interpreter->getName(),
			$interpreter->isNullableObject(),
			$interpreter->isInList(),
			$interpreter->isNullableList()
		);
	}

	/**
	 * @param ObjectTypeInterpreter $interpreter
	 * @param StubFormatter $formatter
	 * @return ObjectType
	 */
	public function createObjectGeneratorType($interpreter, $formatter) {
		return new ObjectType(
			$interpreter->getName(),
			$formatter,
			$interpreter->getFields(),
			$interpreter->getDescription()
		);
	}

	/**
	 * @param ScalarTypeInterpreter $interpreter
	 * @param StubFormatter $formatter
	 * @return ScalarType
	 */
	public function createScalarGeneratorType($interpreter, $formatter) {
		return new ScalarType(
			$interpreter->getName(),
			$formatter,
			$interpreter->getDescription()
		);
	}
}