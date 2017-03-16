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
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenEnum_getDependencies_WillBeEmpty() {
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenEnumWith3ValuesNonOptimized_getConstantsDeclaration_WillContainValuesNames() {
	    $enum = $this->GivenEnumWith3Values();
	    $enum->getFormatter()->optimizeEnums = false;

	    $retVal = $enum->getVariablesDeclarations();

	    $this->assertContains("'" . self::ENUM_VALUE_NAME_1 . "'", $retVal);
	    $this->assertContains("'" . self::ENUM_VALUE_NAME_2 . "'", $retVal);
	    $this->assertContains("'" . self::ENUM_VALUE_NAME_3 . "'", $retVal);
    }

	public function test_GivenEnumWith3ValuesNonOptimized_getConstantsDeclaration_WontContainValuesNames() {
	    $enum = $this->GivenEnumWith3Values();
        $enum->getFormatter()->optimizeEnums = true;

	    $retVal = $enum->getVariablesDeclarations();

	    $this->assertNotContains("'" . self::ENUM_VALUE_NAME_1 . "'", $retVal);
	    $this->assertNotContains("'" . self::ENUM_VALUE_NAME_2 . "'", $retVal);
	    $this->assertNotContains("'" . self::ENUM_VALUE_NAME_3 . "'", $retVal);
    }

	public function test_GivenEnumWith3ValuesNonOptimized_getConstantsDeclaration_WillContainNumbers() {
	    $enum = $this->GivenEnumWith3Values();
        $enum->getFormatter()->optimizeEnums = true;

	    $retVal = $enum->getVariablesDeclarations();

	    $this->assertContains("1", $retVal);
	    $this->assertContains("2", $retVal);
	    $this->assertContains("3", $retVal);
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

	public function test_GivenEnum_generateTypeDefinition_WillContainName() {
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainNameFragment() {
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenEnumWithNoDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$enum = $this->GivenEnumWithNoValues();

		$retVal = $enum->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenEnumWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainValuesFragment() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains("'values'", $retVal);
	}

	public function test_GivenEnumWithDescriptionNoLineJump_generateTypeDefinition_WillContainDescription() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();

		$retVal = $enum->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
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
			[],
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
}