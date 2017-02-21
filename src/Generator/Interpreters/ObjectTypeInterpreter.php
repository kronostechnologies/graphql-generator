<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\ObjectFieldType;

class ObjectTypeInterpreter {
	/**
	 * @var ObjectTypeDefinitionNode
	 */
	protected $_astNode;

	/**
	 * @param ObjectTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return ObjectFieldType[]
	 */
	public function getFields() {
		$fields = [];

		foreach($this->_astNode->fields as $field) {
			$fieldTypeInterpreter = new FieldTypeInterpreter($field->type);

			$newField = new ObjectFieldType();
			$newField->name = $field->name->value;
			$newField->description = $field->description;
			$newField->fieldType = $fieldTypeInterpreter->getFieldType();

			$fields[] = $newField;
		}

		return $fields;
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
}