<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class TypeUsageTest extends \PHPUnit_Framework_TestCase {
	const NON_PRIMARY_TYPE = 'ANonPrimaryType';
	const PRIMARY_TYPE = 'ID';

	public function test_GivenNonPrimaryType_isPrimaryType_WontReturnTrue() {
		$givenTypeUsage = $this->GivenNonPrimaryType();

		$retVal = $givenTypeUsage->isPrimaryType();

		$this->assertFalse($retVal);
	}

	public function test_GivenPrimaryType_isPrimaryType_WillReturnTrue() {
		$givenTypeUsage = $this->GivenPrimaryType();

		$retVal = $givenTypeUsage->isPrimaryType();

		$this->assertTrue($retVal);

	}

	private function GivenNonPrimaryType() {
		return new TypeUsage(
			self::NON_PRIMARY_TYPE,
			false,
			false,
			false
		);
	}

	private function GivenPrimaryType() {
		return new TypeUsage(
			self::PRIMARY_TYPE,
			false,
			false,
			false
		);
	}
}