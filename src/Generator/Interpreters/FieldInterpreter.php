<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldInterpreter extends NestedTypeInterpreter {
	/**
	 * @param FieldDefinitionNode $astNode
	 */
	public function __construct(FieldDefinitionNode $astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return FieldArgument[]
	 */
	public function interpretArguments() {
		return array_map(function ($argumentNode) {
			$argumentInterpreter = new FieldArgumentInterpreter($argumentNode);

			return $argumentInterpreter->generateType();
		}, $this->_astNode->arguments);
	}

	/**
	 * @return string
	 */
	public function interpretDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return \GraphQLGen\Generator\Types\SubTypes\TypeUsage
	 */
	public function interpretType() {
		$typeUsageInterpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $typeUsageInterpreter->generateType();
	}

	/**
	 * @return Field
	 */
	public function generateType() {
		return new Field(
			$this->interpretName(),
			$this->interpretDescription(),
			$this->interpretType(),
			$this->interpretArguments()
		);
	}
}