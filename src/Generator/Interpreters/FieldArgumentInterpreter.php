<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldArgumentInterpreter extends NestedTypeInterpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct(InputValueDefinitionNode $astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return string|null
	 */
	public function interpretDescription() {
		return $this->_astNode->description;
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
			$this->interpretDescription(),
			$this->interpretName(),
			$this->interpretType(),
			$this->interpretDefaultValue()
		);
	}
}