<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class FieldTypeTest extends \PHPUnit_Framework_TestCase {
	const PRIMARY_FIELD_TYPE = "Boolean";
	const SECONDARY_FIELD_TYPE = "SecondaryType";
	const PRIMARY_TYPE_DEPENDENCY = "Type";

	public function test_WithPrimaryType_getDependencies_ReturnsTypeOnly() {
		$fieldType = new TypeUsage(self::PRIMARY_FIELD_TYPE, true, true, true);

		$retVal = $fieldType->getDependencies();

		$expectedDependencies = [ self::PRIMARY_TYPE_DEPENDENCY ];
		$this->assertEquals($expectedDependencies, $retVal);
	}

	public function test_WithSecondaryType_getDependencies_ReturnsSecondaryTypeOnly() {
		$fieldType = new TypeUsage(self::SECONDARY_FIELD_TYPE, true, false, false);

		$retVal = $fieldType->getDependencies();

		$expectedDependencies = [ self::SECONDARY_FIELD_TYPE ];
		$this->assertEquals($expectedDependencies, $retVal);
	}

	public function test_WithSecondaryTypeAndNonNullable_getDependencies_ReturnsBothTypes() {
		$fieldType = new TypeUsage(self::SECONDARY_FIELD_TYPE, false, false, false);

		$retVal = $fieldType->getDependencies();

		$expectedDependencies = [ self::SECONDARY_FIELD_TYPE, self::PRIMARY_TYPE_DEPENDENCY ];
		$this->assertEquals($expectedDependencies, $retVal);
	}

	public function test_WithSecondaryTypeAndInList_getDependencies_ReturnsBothTypes() {
		$fieldType = new TypeUsage(self::SECONDARY_FIELD_TYPE, true, true, true);

		$retVal = $fieldType->getDependencies();

		$expectedDependencies = [ self::SECONDARY_FIELD_TYPE, self::PRIMARY_TYPE_DEPENDENCY ];
		$this->assertEquals($expectedDependencies, $retVal);
	}

	public function test_WithSecondaryTypeAndInListNonNullable_getDependencies_ReturnsBothTypes() {
		$fieldType = new TypeUsage(self::SECONDARY_FIELD_TYPE, true, true, false);

		$retVal = $fieldType->getDependencies();

		$expectedDependencies = [ self::SECONDARY_FIELD_TYPE, self::PRIMARY_TYPE_DEPENDENCY ];
		$this->assertEquals($expectedDependencies, $retVal);
	}
}