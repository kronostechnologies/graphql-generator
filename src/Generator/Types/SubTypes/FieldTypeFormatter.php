<?php


namespace GraphQLGen\Generator\Types\SubTypes;


abstract class FieldTypeFormatter {
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
			$typeDeclaration = $this->getFieldTypeDeclarationNonPrimaryType($fieldType);
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

	/**
	 * @param FieldType $fieldType
	 * @return string
	 */
	abstract public function getFieldTypeDeclarationNonPrimaryType($fieldType);
}