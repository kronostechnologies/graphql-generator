<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\TypeNode;
use GraphQLGen\Generator\Types\SubTypes\FieldType;

class FieldTypeInterpreter {
	/**
	 * @var ListTypeNode|NamedTypeNode|NonNullTypeNode
	 */
	protected $_astNode;

	/**
	 * FieldTypeInterpreter constructor.
	 * @param NonNullTypeNode|ListTypeNode|NamedTypeNode|TypeNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return bool
	 */
	public function isInList() {
		if($this->_astNode->kind === NodeKind::NON_NULL_TYPE) {
			return $this->_astNode->type->kind === NodeKind::LIST_TYPE;
		}
		else {
			return $this->_astNode->kind === NodeKind::LIST_TYPE;
		}
	}

	/**
	 * @return bool
	 */
	public function isNullableList() {
		if(!$this->isInList()) {
			return false;
		}
		else if($this->_astNode->kind === NodeKind::NON_NULL_TYPE) {
			return $this->_astNode->type->kind !== NodeKind::LIST_TYPE;
		}
		else {
			return $this->_astNode->kind === NodeKind::LIST_TYPE;
		}
	}

	/**
	 * @return bool
	 */
	public function isNullableObject() {
		if($this->_astNode->kind === NodeKind::NON_NULL_TYPE) {
			return $this->isInList() ? $this->_astNode->type->type->kind !== NodeKind::NON_NULL_TYPE : false;
		}
		else {
			return $this->isInList() ? $this->_astNode->type->kind !== NodeKind::NON_NULL_TYPE : true;
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		// Finds name node
		$nameNode = $this->_astNode;
		while($nameNode->kind !== NodeKind::NAMED_TYPE) {
			$nameNode = $nameNode->type;
		}

		return $nameNode->name->value;
	}
}