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
	 * @param string $typeName
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($typeName) {
		return ClassComposer::TYPE_STORE_CLASS_NAME . '::' . $typeName . '()';
	}

    /**
     * @param string $typeName
     * @return string
     */
	public function getResolveSnippet($typeName)
    {
        $fieldNameUpperCased = ucwords($typeName);

        return "'resolver' => function (\$root, \$args) { \$this->resolver->resolve{$fieldNameUpperCased}(\$root, \$args); }";
    }
}