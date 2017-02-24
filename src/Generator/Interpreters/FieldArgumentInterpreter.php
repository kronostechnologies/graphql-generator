<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\FieldArgument;

class FieldArgumentInterpreter extends Interpreter {
	/**
	 * @param InputValueDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return string|null
	 */
	public function getDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		if($this->_astNode->defaultValue === null) {
			return null;
		}
		else {
			return $this->_astNode->defaultValue->value;
		}
	}

	/**
	 * @return \GraphQLGen\Generator\Types\SubTypes\TypeUsage
	 */
	public function getType() {
		$interpreter = new TypeUsageInterpreter($this->_astNode->type);

		return $interpreter->generateType();
	}

	/**
	 * @param null $formatter
	 * @return FieldArgument
	 */
	public function generateType($formatter = null) {
		return new FieldArgument(
			$this->getDescription(),
			$this->getName(),
			$this->getType(),
			$this->getDefaultValue()
		);
	}
}