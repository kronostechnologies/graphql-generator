<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\FieldType;
use GraphQLGen\Generator\Types\SubTypes\FieldTypeFormatter;

class PSR4FieldTypeFormatter extends FieldTypeFormatter {
	/**
	 * @param FieldType $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		// Primary type check
		if (in_array($fieldType->typeName, FieldType::$PRIMARY_TYPES_MAPPINGS)) {
			$typeDeclaration = FieldType::$PRIMARY_TYPES_MAPPINGS[$fieldType->typeName];
		}
		else {
			$typeDeclaration = 'TypeStore::get' . $fieldType->typeName . '()';
		}

		// Is base object nullable?
		if(!$fieldType->isTypeNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		// Is in list?
		if($fieldType->inList) {
			$typeDeclaration = 'Type::listOf(' . $typeDeclaration . ')';
		}

		// Is list nullable?
		if($fieldType->inList && !$fieldType->isListNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		return $typeDeclaration;
	}
}