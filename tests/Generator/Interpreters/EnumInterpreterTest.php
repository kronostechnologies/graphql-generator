<?php


namespace GraphQLGen\Tests\Generator\Interpreters;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class EnumInterpreterTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'TestDescription';
	const VALID_NAME = 'TestName';
	const ENUM_VALUE_NAME_1 = 'ENUM_VAL_1';
	const ENUM_VALUE_DESC_1 = 'First enumeration value.';
	const ENUM_VALUE_NAME_2 = 'ENUM_VAL_2';
	const ENUM_VALUE_DESC_2 = null;
	const ENUM_VALUE_NAME_3 = 'ENUM_VAL_3';
	const ENUM_VALUE_DESC_3 = 'The last element.';

	public function test_GivenNodeWithName_getName_ReturnsCorrectName() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithName($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedName = $interpreter->getName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedDescription = $interpreter->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenNodeWithoutEnumValues_getEnumValues_ReturnsEmptyArray() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithEmptyValues($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->getEnumValues();

		$this->assertEmpty($interpretedValues);
	}

	protected function GivenNodeWithEmptyValues($node) {
		$node->values = [];
	}

	public function test_GivenNodeWithSingleEnumValue_getEnumValues_ReturnsSingleElement() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithSingleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->getEnumValues();

		$this->assertCount(1, $interpretedValues);
	}

	protected function GivenNodeWithSingleEnumValue($node) {
		$node->values = [];

		$newEnumValueNode = new EnumValueDefinitionNode([]);
		$newEnumValueNode->name = new NameNode([]);
		$newEnumValueNode->name->value = self::ENUM_VALUE_NAME_1;
		$newEnumValueNode->description = self::ENUM_VALUE_DESC_1;

		$node->values[] = $newEnumValueNode;
	}

	public function test_GivenNodeWithSingleEnumValue_getEnumValues_ReturnsRightElement() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithSingleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->getEnumValues();

		$this->assertContainsOnly(
			new EnumValue(self::ENUM_VALUE_NAME_1, self::ENUM_VALUE_DESC_1),
			$interpretedValues
		);
	}

	public function test_GivenMultipleElements_getEnumValues_ReturnsRightNumberOfElements() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithMultipleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->getEnumValues();

		$this->assertCount(3, $interpretedValues);
	}

	public function test_GivenMultipleElements_getEnumValues_ReturnsRightElements() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithMultipleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->getEnumValues();

		$this->assertContains(
			new EnumValue(self::ENUM_VALUE_NAME_1, self::ENUM_VALUE_DESC_1),
			$interpretedValues,
			'',
			false,
			false
		);
		$this->assertContains(
			new EnumValue(self::ENUM_VALUE_NAME_2, self::ENUM_VALUE_DESC_2),
			$interpretedValues,
			'',
			false,
			false
		);
		$this->assertContains(
			new EnumValue(self::ENUM_VALUE_NAME_3, self::ENUM_VALUE_DESC_3),
			$interpretedValues,
			'',
			false,
			false
		);
	}

	public function test_GivenNodeWithInformation_generateType_WillReturnRightType() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithName($enumNode);
		$this->GivenNodeWithSingleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$retVal = $interpreter->generateType(new StubFormatter());

		$this->assertInstanceOf(Enum::class, $retVal);
	}

	protected function GivenNodeWithMultipleEnumValue($node) {
		$node->values = [];

		$newEnumValueNode = new EnumValueDefinitionNode([]);
		$newEnumValueNode->name = new NameNode([]);
		$newEnumValueNode->name->value = self::ENUM_VALUE_NAME_1;
		$newEnumValueNode->description = self::ENUM_VALUE_DESC_1;

		$node->values[] = $newEnumValueNode;

		$newEnumValueNode = new EnumValueDefinitionNode([]);
		$newEnumValueNode->name = new NameNode([]);
		$newEnumValueNode->name->value = self::ENUM_VALUE_NAME_2;
		$newEnumValueNode->description = self::ENUM_VALUE_DESC_2;

		$node->values[] = $newEnumValueNode;

		$newEnumValueNode = new EnumValueDefinitionNode([]);
		$newEnumValueNode->name = new NameNode([]);
		$newEnumValueNode->name->value = self::ENUM_VALUE_NAME_3;
		$newEnumValueNode->description = self::ENUM_VALUE_DESC_3;

		$node->values[] = $newEnumValueNode;
	}

	protected function GivenNodeWithDescription($node) {
		$node->description = self::VALID_DESCRIPTION;
	}

	protected function GivenNodeWithName($node) {
		$node->name = new NameNode([]);
		$node->name->value = self::VALID_NAME;
	}
}