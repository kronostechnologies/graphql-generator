<?php


namespace GraphQLGen\Generator\Writer\Namespaced;

use GraphQLGen\Generator\Writer\BaseTypeFormatter;

/**
 * Required formatter for PSR-4 standards.
 *
 * Class WithResolverFormatter
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class WithResolverFormatter extends BaseTypeFormatter {
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
	public function resolveFieldTypeDeclarationDocComment($typeName) {
		return $typeName . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX;
	}

    /**
     * @param string $fieldName
     * @param string $typeName
     * @return string
     */
	public function getResolveSnippet($fieldName, $typeName)
    {
        $fieldNameUpperCased = ucwords($fieldName);

        return "function (\$root, \$args) { return \$this->resolver->resolve{$fieldNameUpperCased}(\$root, \$args); }";
    }

    /**
     * @return string
     */
    public function getResolveSnippetForUnion()
    {
        return "function (\$value, \$context, GraphQL\\Type\\Definition\\ResolveInfo \$info) { return \$this->resolver->resolve(\$value, \$context, \$info); }";
    }

    /**
     * @param string $typeName
     * @return string
     */
	public function getInterfaceResolveSnippet($typeName) {
		return "function (\$value) { return \$this->resolver->resolveType(\$value); }";
	}
}