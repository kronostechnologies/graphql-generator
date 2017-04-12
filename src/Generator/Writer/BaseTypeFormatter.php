<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

abstract class BaseTypeFormatter {
	/**
	 * @param TypeUsageInterpretedType $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		// Primary type check
		if(isset(TypeUsageInterpretedType::$PRIMARY_TYPES_MAPPINGS[$fieldType->getTypeName()])) {
			$typeDeclaration = TypeUsageInterpretedType::$PRIMARY_TYPES_MAPPINGS[$fieldType->getTypeName()];
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
	public abstract function getResolveSnippet($typeName);

    /**
     * @return string
     */
    public abstract function getResolveSnippetForUnion();

	/**
	 * @return string
	 */
    public abstract function getInterfaceResolveSnippet();

	/**
	 * @param TypeUsageInterpretedType $fieldType
	 * @return string
	 */
	public function resolveFieldTypeDocComment($fieldType) {
		// Primary type check
		if(isset(TypeUsageInterpretedType::$PRIMARY_TYPES_DOCCOMMENTS[$fieldType->getTypeName()])) {
			$typeDeclaration = TypeUsageInterpretedType::$PRIMARY_TYPES_DOCCOMMENTS[$fieldType->getTypeName()];
		}
		else {
			// Complex types, as relationships
			$typeDeclaration = "mixed";
		}

		// Append array notation
		if($fieldType->isInList()) {
			$typeDeclaration .= '[]';
		}

		// Append null notation
		if (($fieldType->isInList() && $fieldType->isListNullable()) ||
			(!$fieldType->isInList() && $fieldType->isTypeNullable())) {
			$typeDeclaration .= "|null";
		}


		return $typeDeclaration;
	}
}