<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class TypeUsageTest extends \PHPUnit_Framework_TestCase {
	const NON_PRIMARY_TYPE = 'ANonPrimaryType';
	const PRIMARY_TYPE = 'ID';
	const CONSTRUCTED_TYPE_NAME = 'HelloType';

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

	public function test_GivenTypeUsageConstructedName_getTypeName_WillReturnTypeName() {
		$givenTypeUsage = $this->GivenTypeUsageConstructedName();

		$retVal = $givenTypeUsage->getTypeName();

		$this->assertEquals(self::CONSTRUCTED_TYPE_NAME, $retVal);
	}

	public function test_GivenTypeUsageConstructedIsTypeNullableTrue_isTypeNullable_WillReturnTrue() {
		$givenTypeUsage = $this->GivenTypeUsageConstructedIsTypeNullableTrue();

		$retVal = $givenTypeUsage->isTypeNullable();

		$this->assertTrue($retVal);
	}

	public function test_GivenTypeUsageConstructedInListTrue_isInList_WillReturnTrue() {
		$givenTypeUsage = $this->GivenTypeUsageConstructedInListTrue();

		$retVal = $givenTypeUsage->isInList();

		$this->assertTrue($retVal);
	}

	public function test_GivenTypeUsageConstructedIsListNullableTrue_isListNullable_WillReturnTrue() {
		$givenTypeUsage = $this->GivenTypeUsageConstructedIsListNullableTrue();

		$retVal = $givenTypeUsage->isListNullable();

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

	private function GivenTypeUsageConstructedName() {
		return new TypeUsage(
			self::CONSTRUCTED_TYPE_NAME,
			false,
			false,
			false
		);
	}

	private function GivenTypeUsageConstructedIsTypeNullableTrue() {
		return new TypeUsage(
			self::CONSTRUCTED_TYPE_NAME,
			true,
			false,
			false
		);
	}

	private function GivenTypeUsageConstructedInListTrue() {
		return new TypeUsage(
			self::CONSTRUCTED_TYPE_NAME,
			false,
			true,
			false
		);
	}

	private function GivenTypeUsageConstructedIsListNullableTrue() {
		return new TypeUsage(
			self::CONSTRUCTED_TYPE_NAME,
			false,
			false,
			true
		);
	}


}