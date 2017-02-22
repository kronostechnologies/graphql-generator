<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\FieldType;
use GraphQLGen\Generator\Types\SubTypes\FieldTypeFormatter;

class PSR4FieldTypeFormatter extends FieldTypeFormatter {
	/**
	 * @param FieldType $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($fieldType) {
		return 'TypeStore::get' . $fieldType->typeName . '()';
	}
}