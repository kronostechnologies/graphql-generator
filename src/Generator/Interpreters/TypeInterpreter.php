<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\GeneratorFactory;
use GraphQLGen\Generator\Types\SubTypes\ObjectFieldType;

class TypeInterpreter {
	/**
	 * @var ObjectTypeDefinitionNode
	 */
	protected $_astNode;

	/**
	 * @var GeneratorFactory
	 */
	protected $_factory;

	/**
	 * @param ObjectTypeDefinitionNode $astNode
	 * @param GeneratorFactory $factory
	 */
	public function __construct($astNode, $factory) {
		$this->_astNode = $astNode;
		$this->_factory = $factory;
	}

	/**
	 * @return ObjectFieldType[]
	 */
	public function getFields() {
		return array_map(function ($field) {
			$fieldTypeInterpreter = $this->_factory->createFieldTypeInterpreter($field->type);

			return $this->_factory->createFieldTypeGeneratorType($fieldTypeInterpreter);
		}, $this->_astNode->fields);
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