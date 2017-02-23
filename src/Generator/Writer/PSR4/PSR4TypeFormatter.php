<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\TypeFormatter;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class PSR4TypeFormatter extends TypeFormatter {
	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($fieldType) {
		return 'TypeStore::getTypeDefinition(' . $fieldType->typeName . '::class)';
	}
}