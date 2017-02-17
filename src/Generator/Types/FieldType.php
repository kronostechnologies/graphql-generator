<?php


namespace GraphQLGen\Generator\Types;


class FieldType {
	/**
	 * @var string
	 */
	public $typeName;

	/**
	 * @var bool
	 */
	public $inList;

	/**
	 * @var bool
	 */
	public $isTypeNullable;

	/**
	 * @var bool
	 */
	public $isListNullable;

	public function __construct($type_name, $is_type_nullable, $in_list, $is_list_nullable) {
		$this->typeName = $type_name;
		$this->isTypeNullable = $is_type_nullable;
		$this->inList = $in_list;
		$this->isListNullable = $is_list_nullable;
	}

	public function getFieldType() {
		$typeDeclaration = 'TypeStore::get' . $field->fieldType->typeName . '()';

		// Is base object nullable?
		if(!$field->fieldType->isTypeNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		// Is in list?
		if($field->fieldType->inList) {
			$typeDeclaration = 'Type::listOf(' . $typeDeclaration . ')';
		}

		// Is list nullable?
		if($field->fieldType->inList && !$field->fieldType->isListNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		return $typeDeclaration;
	}
}