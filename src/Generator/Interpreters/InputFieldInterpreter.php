<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\InputField;

class InputFieldInterpreter extends NestedTypeInterpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return InputField
	 */
	public function generateType() {
		return new InputField(
			$this->interpretName(),
			$this->interpretDescription(),
			$this->interpretType()
		);
	}

	/**
	 * @return \GraphQLGen\Generator\Types\SubTypes\TypeUsage
	 */
	public function interpretType() {
		$typeUsageInterpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $typeUsageInterpreter->generateType();
	}
}