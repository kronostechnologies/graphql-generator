<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class BaseTypeFormatter {
	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		// Primary type check
		if(isset(TypeUsage::$PRIMARY_TYPES_MAPPINGS[$fieldType->getTypeName()])) {
			$typeDeclaration = TypeUsage::$PRIMARY_TYPES_MAPPINGS[$fieldType->getTypeName()];
		}
		else {
			$typeDeclaration = $this->getFieldTypeDeclarationNonPrimaryType($fieldType->getTypeName());
		}

		// Is base object nullable?
		if(!$fieldType->isTypeNullable()) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		// Is in list?
		if($fieldType->isInList()) {
			$typeDeclaration = 'Type::listOf(' . $typeDeclaration . ')';
		}

		// Is list nullable?
		if($fieldType->isInList() && !$fieldType->isListNullable()) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		return $typeDeclaration;
	}

	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function resolveFieldTypeDocComment($fieldType) {
		// Primary type check
		if(isset(TypeUsage::$PRIMARY_TYPES_DOCCOMMENTS[$fieldType->getTypeName()])) {
			$typeDeclaration = TypeUsage::$PRIMARY_TYPES_DOCCOMMENTS[$fieldType->getTypeName()];
		}
		else {
			$typeDeclaration = $this->resolveFieldTypeDeclarationDocComment($fieldType->getTypeName());
		}

		// Append array notation
		if($fieldType->isInList()) {
			$typeDeclaration .= '[]';
		}

		return $typeDeclaration;
	}

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($typeName) {
		return $typeName;
	}

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function resolveFieldTypeDeclarationDocComment($typeName) {
		return $typeName;
	}

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getResolveSnippet($typeName) {
		return '';
	}
}