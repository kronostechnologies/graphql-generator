<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\SubTypes\Field;

class InterfaceInterpreter extends MainTypeInterpreter {
	/**
	 * @param InterfaceTypeDefinitionNode $astNode
	 */
	public function __construct(InterfaceTypeDefinitionNode $astNode) {
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
	 * @return Field[]
	 */
	public function getFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new FieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields);
	}

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return InterfaceDeclaration
	 */
	public function generateType($formatter) {
		return new InterfaceDeclaration(
			$this->getName(),
			$this->getFields(),
			$formatter,
			$this->getDescription()
		);
	}
}