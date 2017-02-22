<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\FieldInterpreter;
use GraphQLGen\Generator\Interpreters\TypeInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Generator\Types\EnumType;
use GraphQLGen\Generator\Types\ObjectType;
use GraphQLGen\Generator\Types\ScalarType;
use GraphQLGen\Generator\Types\SubTypes\FieldType;

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

	/**
	 * @param $astNode
	 * @return TypeInterpreter
	 */
	public function createObjectTypeInterpreter($astNode) {
		return new TypeInterpreter($astNode);
	}

	/**
	 * @param EnumInterpreter $interpreter
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
	 * @param FieldInterpreter $interpreter
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
	 * @param TypeInterpreter $interpreter
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
	 * @param ScalarInterpreter $interpreter
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

	/**
	 * @param FieldInterpreter $type
	 * @return FieldInterpreter
	 */
	public function createFieldTypeInterpreter($type) {
		return new FieldInterpreter($type);
	}
}