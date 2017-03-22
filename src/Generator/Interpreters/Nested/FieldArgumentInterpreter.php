<?php


namespace GraphQLGen\Generator\Interpreters\Nested;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldArgumentInterpreter extends NestedTypeInterpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return mixed
	 */
	public function interpretDefaultValue() {
		if($this->_astNode->defaultValue === null) {
			return null;
		}

		return $this->_astNode->defaultValue->value;
	}

	/**
	 * @return \GraphQLGen\Generator\Types\SubTypes\TypeUsage
	 */
	public function interpretType() {
		$interpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $interpreter->generateType();
	}

	/**
	 * @return FieldArgument
	 */
	public function generateType() {
		return new FieldArgument(
			$this->interpretName(), $this->interpretDescription(), $this->interpretType(), $this->interpretDefaultValue()
		);
	}
}