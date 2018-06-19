<?php


namespace GraphQLGen\Tests\Old\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Nested\EnumValueFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

class EnumValueFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const ENUM_VALUE_DESC = 'A Description';
	const ENUM_VALUE_NAME = 'AnEnumeration';

	public function test_GivenEnumValueWithoutDescription_getVariablesDeclarations_WillContainConst() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->getVariablesDeclarations();

		$this->assertContains("const ", $retVal);
	}

	public function test_GivenEnumValueWithoutDescription_generateTypeDefinition_WillContainSelfReference() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("self::", $retVal);

	}

	public function test_GivenEnumValueWithoutDescription_generateTypeDefinition_WillContainEnumValueName() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::ENUM_VALUE_NAME, $retVal);
	}

	public function test_GivenEnumValueWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenEnumValueWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$enumValue = $this->GivenEnumValueWithDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenEnumValueWithoutDescription_generateTypeDefinition_GeneratesShorthand() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'" . self::ENUM_VALUE_NAME ."'", $retVal);
		$this->assertContains('self::', $retVal);
	}

	public function test_GivenEnumValueWithDescription_generateTypeDefinition_GeneratesLongDefinition() {
		$enumValue = $this->GivenEnumValueWithDescription();
		$generator = $this->GivenEnumValueGenerator($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'" . self::ENUM_VALUE_NAME ."'", $retVal);
		$this->assertContains('self::', $retVal);
	}

	public function test_GivenEnumValueWithoutDescriptionNoShorthands_generateTypeDefinition_GeneratesLongDefinition() {
		$enumValue = $this->GivenEnumValueWithoutDescription();
		$generator = $this->GivenEnumValueGeneratorForcedLongFormEnums($enumValue);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'" . self::ENUM_VALUE_NAME ."'", $retVal);
		$this->assertContains('self::', $retVal);
	}

	protected function GivenEnumValueWithoutDescription() {
		$enumValue = new EnumValueInterpretedType();
		$enumValue->setName(self::ENUM_VALUE_NAME);

		return $enumValue;
	}

	protected function GivenEnumValueWithDescription() {
		$enumValue = new EnumValueInterpretedType();
		$enumValue->setName(self::ENUM_VALUE_NAME);
		$enumValue->setDescription(self::ENUM_VALUE_DESC);

		return $enumValue;
	}

	protected function GivenEnumValueGenerator($type) {
		$formatter = new StubFormatter();
		$formatter->longFormEnums = false;

		$generator = new EnumValueFragmentGenerator();
		$generator->setEnumValue($type);
		$generator->setFormatter($formatter);

		return $generator;
	}

	protected function GivenEnumValueGeneratorForcedLongFormEnums($type) {
		$formatter = new StubFormatter();
		$formatter->longFormEnums = true;

		$generator = new EnumValueFragmentGenerator();
		$generator->setEnumValue($type);
		$generator->setFormatter($formatter);

		return $generator;
	}
}