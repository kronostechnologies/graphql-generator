<?php


namespace GraphQLGen\Tests\Mocks\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;

class TestableBaseTypeFormatter extends BaseTypeFormatter {

	/**
	 * @param string $typeName
	 * @return string
	 */
	public function getResolveSnippet($typeName) {
		return "";
	}
}