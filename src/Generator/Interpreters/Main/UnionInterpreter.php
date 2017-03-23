<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Generator\Interpreters\Nested\TypeUsageInterpreter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Union;

class UnionInterpreter extends MainTypeInterpreter {
	/**
	 * @param UnionTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return UnionInterpretedType
	 */
	public function generateType() {
		$interpretedType = new UnionInterpretedType();
		$interpretedType->setName($this->interpretName());
		$interpretedType->setDescription($this->interpretDescription());
		$interpretedType->setTypes($this->interpretTypes());

		return $interpretedType;
	}

	/**
	 * @return TypeUsageInterpretedType[]
	 */
	public function interpretTypes() {
		return array_map(function ($typeNode) {
			$typeUsageInterpreter = new TypeUsageInterpreter($typeNode);

			return $typeUsageInterpreter->generateType();
		}, $this->_astNode->types);
	}
}