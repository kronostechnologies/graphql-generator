<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\UnionTypeDefinitionNode;
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
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Union
	 */
	public function generateType($formatter) {
		return new Union(
			$this->interpretName(),
			$formatter,
			$this->interpretTypes(),
			$this->interpretDescription()
		);
	}

	/**
	 * @return TypeUsage[]
	 */
	public function interpretTypes() {
		return array_map(function ($typeNode) {
			$typeUsageInterpreter = new TypeUsageInterpreter($typeNode);

			return $typeUsageInterpreter->generateType();
		}, $this->_astNode->types);
	}
}