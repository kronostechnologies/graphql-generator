<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\SubTypes\TypeFormatter;

class PSR4TypeFormatter extends TypeFormatter {
	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($fieldType) {
		return 'TypeStore::get' . $fieldType->typeName . '()';
	}
}