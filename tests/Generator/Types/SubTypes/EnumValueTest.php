<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumValueTest extends \PHPUnit_Framework_TestCase {
	const ENUM_VALUE_NAME = "EnumValue";
	const ENUM_VALUE_DESC = "EnumDescription";

	public function test_GivenEnumValue_setNameThenGetName_WillReturnName() {
		$enumValue = $this->GivenEnumValue();

		$enumValue->setName(self::ENUM_VALUE_NAME);
		$retVal = $enumValue->getName();

		$this->assertEquals(self::ENUM_VALUE_NAME, $retVal);
	}

	public function test_GivenEnumValue_setDescriptionThenGetDescription_WillReturnDescription() {
		$enumValue = $this->GivenEnumValue();

		$enumValue->setDescription(self::ENUM_VALUE_DESC);
		$retVal = $enumValue->getDescription();

		$this->assertEquals(self::ENUM_VALUE_DESC, $retVal);
	}

	private function GivenEnumValue() {
		return new EnumValue("", "");
	}
}