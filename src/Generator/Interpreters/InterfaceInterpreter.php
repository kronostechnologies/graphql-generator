<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\SubTypes\Field;

class InterfaceInterpreter extends MainTypeInterpreter {
	/**
	 * @param InterfaceTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return InterfaceDeclaration
	 */
	public function generateType($formatter) {
		return new InterfaceDeclaration(
			$this->interpretName(), $formatter, $this->interpretFields(), $this->interpretDescription()
		);
	}

	/**
	 * @return Field[]
	 */
	public function interpretFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new FieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields);
	}
}