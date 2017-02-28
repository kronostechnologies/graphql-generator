<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumTest extends \PHPUnit_Framework_TestCase {
	const ENUM_VALUE_NAME_1 = 'VAL1';
	const ENUM_VALUE_NAME_2 = 'VAL2';
	const ENUM_VALUE_NAME_3 = 'VAL3';
	const ENUM_VALUE_DESCRIPTION_1 = 'First value of enum';
	const ENUM_VALUE_DESCRIPTION_2 = 'Second value of enum';
	const VALID_NAME = 'AnEnumeration';
	const VALID_DESCRIPTION = 'This is a description of the enumeration';

	public function test_GivenEnum_getName_WillReturnName() {
		$enum = $this->GivenEnum();

		$retVal = $enum->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenEnum_getDependencies_WillBeEmpty() {
		$enum = $this->GivenEnum();

		$retVal = $enum->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenEnumWithNoValues_getConstantsDeclaration_WillBeEmpty() {
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->getVariablesDeclarations();

		$this->assertEmpty($retVal);
	}

	public function test_GivenEnumWith3Values_getConstants_WillContain3Const() {
		$enum = $this->GivenEnumWith3Values();

		$retVal = $enum->getVariablesDeclarations();

		$this->assertEquals(3, substr_count($retVal, "const "));
	}

	public function test_GivenEnumWith3ConstantsAndNoContantsFormatter_generateTypeDefinition_WontReturnSelfReferences() {
		$enum = $this->GivenEnumWith3ConstantsAndNoContantsFormatter();

		$retVal = $enum->generateTypeDefinition();

		$this->assertNotContains("self::", $retVal);
	}

	public function test_GivenEnumWith3ConstantsAndConstantsFormatter_generateTypeDefinition_WillReturnSelfReferences() {
		$enum = $this->GivenEnumWith3ConstantsAndConstantsFormatter();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains("self::", $retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainName() {
		$enum = $this->GivenEnum();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenEnumWithDescriptionNoLineJump_generateTypeDefinition_WillContainDescription() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	protected function GivenEnum() {
		return new Enum(
			self::VALID_NAME,
			null,
			new StubFormatter()
		);
	}

	protected function GivenEnumWithNoValues() {
		return new Enum(
			self::VALID_NAME,
			[],
			new StubFormatter()
		);
	}

	protected function GivenEnumWithDescriptionNoLineJump() {
		return new Enum(
			self::VALID_NAME,
			null,
			new StubFormatter(),
			self::VALID_DESCRIPTION
		);
	}

	protected function GivenEnumWith3Values() {
		$enumValue1 = new EnumValue(
			self::ENUM_VALUE_NAME_1,
			self::ENUM_VALUE_DESCRIPTION_1
		);
		$enumValue2 = new EnumValue(
			self::ENUM_VALUE_NAME_2,
			self::ENUM_VALUE_DESCRIPTION_2
		);
		$enumValue3 = new EnumValue(
			self::ENUM_VALUE_NAME_3,
			null
		);

		return new Enum(
			self::VALID_NAME,
			[$enumValue1, $enumValue2, $enumValue3],
			new StubFormatter()
		);
	}

	protected function GivenEnumWith3ConstantsAndNoContantsFormatter() {
		$enumValue1 = new EnumValue(
			self::ENUM_VALUE_NAME_1,
			self::ENUM_VALUE_DESCRIPTION_1
		);
		$enumValue2 = new EnumValue(
			self::ENUM_VALUE_NAME_2,
			self::ENUM_VALUE_DESCRIPTION_2
		);
		$enumValue3 = new EnumValue(
			self::ENUM_VALUE_NAME_3,
			null
		);

		return new Enum(
			self::VALID_NAME,
			[$enumValue1, $enumValue2, $enumValue3],
			new StubFormatter(true, 4, ",", null, false)
		);
	}

	protected function GivenEnumWith3ConstantsAndConstantsFormatter() {
		$enumValue1 = new EnumValue(
			self::ENUM_VALUE_NAME_1,
			self::ENUM_VALUE_DESCRIPTION_1
		);
		$enumValue2 = new EnumValue(
			self::ENUM_VALUE_NAME_2,
			self::ENUM_VALUE_DESCRIPTION_2
		);
		$enumValue3 = new EnumValue(
			self::ENUM_VALUE_NAME_3,
			null
		);

		return new Enum(
			self::VALID_NAME,
			[$enumValue1, $enumValue2, $enumValue3],
			new StubFormatter(true, 4, ",", null, true)
		);
	}
}