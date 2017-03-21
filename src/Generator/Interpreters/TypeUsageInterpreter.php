<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\TypeNode;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class TypeUsageInterpreter extends NestedTypeInterpreter {
	/**
	 * FieldTypeInterpreter constructor.
	 * @param NonNullTypeNode|ListTypeNode|NamedTypeNode|TypeNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return TypeUsage
	 */
	public function generateType() {
		return new TypeUsage(
			$this->interpretName(),
			$this->isNullableObject(),
			$this->isInList(),
			$this->isNullableList()
		);
	}

	/**
	 * @return string
	 */
	public function interpretDescription() {
		return "";
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		// Finds name node
		$nameNode = $this->_astNode;
		while($nameNode->kind !== NodeKind::NAMED_TYPE) {
			$nameNode = $nameNode->type;
		}

		return $nameNode->name->value;
	}

	/**
	 * @return bool
	 */
	public function isInList() {
		if($this->_astNode->kind === NodeKind::NON_NULL_TYPE) {
			return $this->_astNode->type->kind === NodeKind::LIST_TYPE;
		}

		return $this->_astNode->kind === NodeKind::LIST_TYPE;
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

		return $this->_astNode->kind === NodeKind::LIST_TYPE;
	}

	/**
	 * @return bool
	 */
	public function isNullableObject() {
		if($this->_astNode->kind === NodeKind::NON_NULL_TYPE) {
			return $this->isInList() ? $this->_astNode->type->type->kind !== NodeKind::NON_NULL_TYPE : false;
		}

		return $this->isInList() ? $this->_astNode->type->kind !== NodeKind::NON_NULL_TYPE : true;
	}
}