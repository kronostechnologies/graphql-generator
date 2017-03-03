<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class BaseTypeFormatter {
	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		// Primary type check
		if(isset(TypeUsage::$PRIMARY_TYPES_MAPPINGS[$fieldType->typeName])) {
			$typeDeclaration = TypeUsage::$PRIMARY_TYPES_MAPPINGS[$fieldType->typeName];
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
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($fieldType) {
		return $fieldType->typeName;
	}
}