<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Main;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

class EnumFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const ENUM_VALUE_NAME_1 = 'VAL1';
	const ENUM_VALUE_NAME_2 = 'VAL2';
	const ENUM_VALUE_NAME_3 = 'VAL3';
	const ENUM_VALUE_DESCRIPTION_1 = 'First value of enum';
	const ENUM_VALUE_DESCRIPTION_2 = 'Second value of enum';
	const VALID_NAME = 'AnEnumeration';
	const VALID_DESCRIPTION = 'This is a description of the enumeration';

	public function test_GivenEnumWithNoValues_getVariablesDeclarations_WillBeEmpty() {
		$enum = $this->GivenEnumWithNoValues();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->getVariablesDeclarations();

		$this->assertEmpty($retVal);
	}

	public function test_GivenEnumWith3Values_getConstants_WontBeEmpty() {
		$enum = $this->GivenEnumWith3Values();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->getVariablesDeclarations();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainName() {
		$enum = $this->GivenEnumWithNoValues();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainNameFragment() {
		$enum = $this->GivenEnumWithNoValues();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenEnumWithNoDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$enum = $this->GivenEnumWithNoValues();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenEnumWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenEnum_generateTypeDefinition_WillContainValuesFragment() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains("'values'", $retVal);
	}

	public function test_GivenEnumWith3Values_generateTypeDefinition_WillContainValuesFragment() {
		$enum = $this->GivenEnumWith3Values();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains("'values'", $retVal);
	}

	public function test_GivenEnumWithDescriptionNoLineJump_generateTypeDefinition_WillContainDescription() {
		$enum = $this->GivenEnumWithDescriptionNoLineJump();
		$fragGen = $this->GivenFragmentGenerator($enum);

		$retVal = $fragGen->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	protected function GivenEnumWithNoValues() {
		$interpretType = new EnumInterpretedType();
		$interpretType->setName(self::VALID_NAME);

		return $interpretType;
	}

	protected function GivenEnumWithDescriptionNoLineJump() {
		$interpretType = new EnumInterpretedType();
		$interpretType->setName(self::VALID_NAME);
		$interpretType->setDescription(self::VALID_DESCRIPTION);

		return $interpretType;
	}

	protected function GivenEnumWith3Values() {
		$enumValue1 = new EnumValueInterpretedType();
		$enumValue1->setName(self::ENUM_VALUE_NAME_1);
		$enumValue1->setDescription(self::ENUM_VALUE_DESCRIPTION_1);

		$enumValue2 = new EnumValueInterpretedType();
		$enumValue2->setName(self::ENUM_VALUE_NAME_2);
		$enumValue2->setDescription(self::ENUM_VALUE_DESCRIPTION_2);

		$enumValue3 = new EnumValueInterpretedType();
		$enumValue3->setName(self::ENUM_VALUE_NAME_3);

		$interpretedType = new EnumInterpretedType();
		$interpretedType->setName(self::VALID_NAME);
		$interpretedType->setDescription(self::VALID_DESCRIPTION);
		$interpretedType->setValues([$enumValue1, $enumValue2, $enumValue3]);

		return $interpretedType;
	}

	protected function GivenFragmentGenerator($enumType) {
		$formatter = new StubFormatter(true, 4, ",", null);
		$fragmentGenerator = new EnumFragmentGenerator();
		$fragmentGenerator->setFormatter($formatter);
		$fragmentGenerator->setEnumType($enumType);

		return $fragmentGenerator;
	}
}