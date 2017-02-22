<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldInterpreter extends Interpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return FieldArgument[]
	 */
	public function getArguments() {
		return array_map(function ($argumentNode) {
			$argumentInterpreter = new FieldArgumentInterpreter($argumentNode);

			return $argumentInterpreter->generateType();
		}, $this->_astNode->arguments);
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return \GraphQLGen\Generator\Types\SubTypes\TypeUsage
	 */
	public function getType() {
		$typeUsageInterpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $typeUsageInterpreter->generateType();
	}

	/**
	 * @param null $formatter
	 * @return Field
	 */
	public function generateType($formatter = null) {
		return new Field(
			$this->getName(),
			$this->getDescription(),
			$this->getType(),
			$this->getArguments()
		);
	}
}