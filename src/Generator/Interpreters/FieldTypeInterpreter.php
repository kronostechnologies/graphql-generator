<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQLGen\Generator\Types\SubTypes\FieldType;

class FieldTypeInterpreter {
	/**
	 * @var ListTypeNode|NamedTypeNode|NonNullTypeNode
	 */
	protected $_astNode;

	/**
	 * FieldTypeInterpreter constructor.
	 * @param NonNullTypeNode|ListTypeNode|NamedTypeNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return FieldType
	 */
	public function getFieldType() {
		$inList = $this->isInList();
		$isNullableList = $this->isNullableList();
		$isNullableObject = $this->isNullableObject();

		// Finds name node
		$nameNode = $this->_astNode;
		while(get_class($nameNode) !== NamedTypeNode::class) {
			$nameNode = $nameNode->type;
		}

		return new FieldType(
			$this->getName($nameNode),
			$isNullableObject,
			$inList,
			$isNullableList
		);
	}

	/**
	 * @return bool
	 */
	public function isInList() {
		if(get_class($this->_astNode) === NonNullTypeNode::class) {
			return get_class($this->_astNode->type) === ListTypeNode::class;
		}
		else {
			return get_class($this->_astNode) === ListTypeNode::class;
		}
	}

	/**
	 * @return bool
	 */
	public function isNullableList() {
		if(get_class($this->_astNode) === NonNullTypeNode::class) {
			return !get_class($this->_astNode->type) === ListTypeNode::class;
		}
		else {
			return get_class($this->_astNode) === ListTypeNode::class;
		}
	}

	/**
	 * @return bool
	 */
	public function isNullableObject() {
		if(get_class($this->_astNode) === NonNullTypeNode::class) {
			return $this->isInList() ? get_class($this->_astNode->type->type) !== NonNullTypeNode::class : false;
		}
		else {
			return $this->isInList() ? get_class($this->_astNode->type) !== NonNullTypeNode::class : true;
		}
	}

	/**
	 * @param NamedTypeNode $nameNode
	 * @return string
	 */
	public function getName($nameNode) {
		return $nameNode->name->value;
	}
}