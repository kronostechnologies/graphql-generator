<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Nested\EnumValueFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

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
		$generator = new EnumValueFragmentGenerator();
		$generator->setEnumValue($type);
		$generator->setFormatter(new StubFormatter());

		return $generator;
	}
}