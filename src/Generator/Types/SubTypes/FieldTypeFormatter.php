<?php


namespace GraphQLGen\Generator\Types\SubTypes;


abstract class FieldTypeFormatter {
	/**
	 * @param FieldType $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclaration($fieldType) {
		return null;
	}
}