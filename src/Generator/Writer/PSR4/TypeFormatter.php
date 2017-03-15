<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

/**
 * Required formatter for PSR-4 standards.
 *
 * Class TypeFormatter
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class TypeFormatter extends BaseTypeFormatter {
	/**
	 * @param TypeUsage $fieldType
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($fieldType) {
		return ClassComposer::TYPE_STORE_CLASS_NAME . '::' . $fieldType->typeName . '()';
	}

    /**
     * @param string $fieldName
     * @return string
     */
	public function getResolveSnippet($fieldName)
    {
        $fieldNameUpperCased = ucwords($fieldName);

        return "'resolver' => function (\$root, \$args) { \$this->resolver->resolve{$fieldNameUpperCased}(\$root, \$args); }";
    }
}