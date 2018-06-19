<?php


namespace GraphQLGen\Tests\Mocks\Types\SubTypes;


use GraphQLGen\Old\Generator\Types\SubTypes\BaseTypeFormatter;

class TestableBaseTypeFormatter extends BaseTypeFormatter {

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getResolveSnippet($typeName) {
		return "";
	}

    /**
     * @return string
     */
    public function getResolveSnippetForUnion() {
        return "";
    }
}