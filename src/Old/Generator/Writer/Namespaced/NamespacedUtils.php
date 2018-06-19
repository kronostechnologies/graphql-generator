<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced;

/**
 * Static utility methods which remain testable without mocking.
 *
 * Class PSR4Utils
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class NamespacedUtils {
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