<?php


namespace GraphQLGen\Old\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Old\Generator\Interpreters\Nested\TypeUsageInterpreter;
use GraphQLGen\Old\Generator\Types\Scalar;
use GraphQLGen\Old\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Old\Generator\Types\Union;

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
		return $this->mapNodeList(function ($typeNode) {
			$typeUsageInterpreter = new TypeUsageInterpreter($typeNode);

			return $typeUsageInterpreter->generateType();
		}, $this->_astNode->types);
	}
}