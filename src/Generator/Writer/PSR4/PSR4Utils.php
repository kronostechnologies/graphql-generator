<?php


namespace GraphQLGen\Generator\Writer\PSR4;

/**
 * Static utility methods which remain testable without mocking.
 *
 * Class PSR4Utils
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class PSR4Utils {
	/**
	 * @param string[] ...$namespaceParts
	 * @return string
	 */
	public static function joinAndStandardizeNamespaces(...$namespaceParts) {
		$namespacePartsWithoutTrailingSlashes = array_map(function ($namespacePart) {
			$namespacePart = str_replace("/", "\\", $namespacePart);
			return trim($namespacePart, "\\");
		}, $namespaceParts);

		return trim(implode("\\", $namespacePartsWithoutTrailingSlashes), "\\");
	}
}