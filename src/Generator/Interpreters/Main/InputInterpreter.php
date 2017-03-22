<?php


namespace GraphQLGen\Generator\Interpreters\Main;


use GraphQL\Language\AST\InputObjectTypeDefinitionNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Interpreters\Nested\InputFieldInterpreter;
use GraphQLGen\Generator\Types\Input;
use GraphQLGen\Generator\Types\SubTypes\Field;

class InputInterpreter extends MainTypeInterpreter {

	/**
	 * @param InputObjectTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @param StubFormatter $formatter
	 * @return Input
	 */
	public function generateType($formatter) {
		return new Input(
			$this->interpretName(),
			$formatter,
			$this->interpretFields()
		);
	}

	/**
	 * @return Field[]
	 */
	public function interpretFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new InputFieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields ?: []);
	}

}