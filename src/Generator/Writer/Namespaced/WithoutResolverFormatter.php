<?php


namespace GraphQLGen\Generator\Writer\Namespaced;


use GraphQLGen\Generator\Writer\BaseTypeFormatter;

class WithoutResolverFormatter extends BaseTypeFormatter {
	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getFieldTypeDeclarationNonPrimaryType($typeName) {
	    // ToDo: Circular reference check goes here
		return "\$typeRegistry->getTypeByName('{$typeName}')";
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
		return "function (\$root, \$args) use (\$queryResolver) { return \$queryResolver->resolveFieldOfType(\$root, \$args, '{$typeName}', '{$fieldName}'); }";
	}

	/**
	 * @return string
	 */
	public function getResolveSnippetForUnion()
	{
	    // ToDo: Not necessary for now
		return "function (\$value, \$context, GraphQL\\Type\\Definition\\ResolveInfo \$info) { return \$this->resolver->resolve(\$value, \$context, \$info); }";
	}

	/**
     * @param string $typeName
	 * @return string
	 */
	public function getInterfaceResolveSnippet($typeName) {
		return "function (\$value) use (\$queryResolver) { return \$queryResolver->resolveInterfaceType('{$typeName}', \$value); }";
	}
}