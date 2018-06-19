<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Old\Generator\Writer\Namespaced\NamespacedUtils;

class PSR4UtilsTest extends \PHPUnit_Framework_TestCase {
	const TRAIL_SLASH_NS = "\\TrailSlash\\NS\\";
	const TRAIL_SLASH_NS_FIXED = "TrailSlash\\NS";

	const MULTI_NS_1 = "Part1";
	const MULTI_NS_2 = "Part2";
	const MULTI_NS_MERGED = "Part1\\Part2";

	const MULTI_NS_SLASHED_1 = "Part1\\";
	const MULTI_NS_SLASHED_2 = "\\Part2";
	const MULTI_NS_SLASHED_MERGED = "Part1\\Part2";

	public function test_GivenTrailingSlashedNamespace_joinAndStandardizeNamespaces_WillStripSlashes() {
		$retVal = NamespacedUtils::joinAndStandardizeNamespaces(self::TRAIL_SLASH_NS);

		$this->assertEquals(self::TRAIL_SLASH_NS_FIXED, $retVal);
	}

	public function test_GivenMultiNSStandard_joinAndStandardizeNamespaces_WillJoinNS() {
		$retVal = NamespacedUtils::joinAndStandardizeNamespaces(self::MULTI_NS_1, self::MULTI_NS_2);

		$this->assertEquals(self::MULTI_NS_MERGED, $retVal);
	}

	public function test_GivenMultiNSWithInvalidSlashes_joinAndStandardizeNamespaces_WillJoinNS() {
		$retVal = NamespacedUtils::joinAndStandardizeNamespaces(self::MULTI_NS_SLASHED_1, self::MULTI_NS_SLASHED_2);

		$this->assertEquals(self::MULTI_NS_SLASHED_MERGED, $retVal);
	}
}