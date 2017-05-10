<?php


namespace GraphQLGen\Generator\Writer\Namespaced;

use GraphQLGen\Generator\Writer\BaseTypeFormatter;

/**
 * Required formatter for PSR-4 standards.
 *
 * Class TypeFormatter
 * @package GraphQLGen\Generator\Writer\Namespaced
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
	public function resolveFieldTypeDeclarationDocComment($typeName) {
		return $typeName . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX;
	}

    /**
     * @param string $typeName
     * @return string
     */
	public function getResolveSnippet($typeName)
    {
        $fieldNameUpperCased = ucwords($typeName);

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
	 * @return string
	 */
	public function getInterfaceResolveSnippet() {
		return "function (\$value) { return \$this->resolver->resolveType(\$value); }";
	}
}