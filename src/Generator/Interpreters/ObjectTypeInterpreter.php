<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\ObjectType;
use GraphQLGen\Generator\Types\SubTypes\ObjectTypeField;

class ObjectTypeInterpreter implements GeneratorInterpreterInterface {
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
	 * @param StubFormatter $formatter
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType($formatter) {
		$fields = $this->getFields();

		return new ObjectType(
			$this->getName(),
			$formatter,
			$fields,
			$this->getDescription()
		);
	}

	/**
	 * @return ObjectTypeField[]
	 */
	public function getFields() {
		$fields = [];

		foreach($this->_astNode->fields as $field) {
			$fieldTypeInterpreter = new FieldTypeInterpreter($field->type);

			$newField = new ObjectTypeField();
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