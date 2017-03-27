<?php


namespace GraphQLGen\Tests\Generator\Interpreters\Main;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\EnumValueDefinitionNode;
use GraphQL\Language\AST\NameNode;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;
use GraphQLGen\Generator\Interpreters\Main\EnumInterpreter;
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
		$interpretedName = $interpreter->interpretName();

		$this->assertEquals(self::VALID_NAME, $interpretedName);
	}

	public function test_GivenNodeWithDescription_getDescription_ReturnsCorrectDescription() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithDescription($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedDescription = $interpreter->interpretDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $interpretedDescription);
	}

	public function test_GivenNodeWithoutEnumValues_getEnumValues_ReturnsEmptyArray() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithEmptyValues($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->interpretValues();

		$this->assertEmpty($interpretedValues);
	}

	protected function GivenNodeWithEmptyValues($node) {
		$node->values = [];
	}

	public function test_GivenNodeWithSingleEnumValue_getEnumValues_ReturnsSingleElement() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithSingleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->interpretValues();

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
		$interpretedValues = $interpreter->interpretValues();

		$enumContains = new EnumValueInterpretedType();
		$enumContains->setName(self::ENUM_VALUE_NAME_1);
		$enumContains->setDescription(self::ENUM_VALUE_DESC_1);
		$this->assertContainsOnly(
			$enumContains,
			$interpretedValues
		);
	}

	public function test_GivenMultipleElements_getEnumValues_ReturnsRightNumberOfElements() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithMultipleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->interpretValues();

		$this->assertCount(3, $interpretedValues);
	}

	public function test_GivenMultipleElements_getEnumValues_ReturnsRightElements() {
		$enumNode = new EnumTypeDefinitionNode([]);
		$this->GivenNodeWithMultipleEnumValue($enumNode);

		$interpreter = new EnumInterpreter($enumNode);
		$interpretedValues = $interpreter->interpretValues();

		$enumContains1 = new EnumValueInterpretedType();
		$enumContains1->setName(self::ENUM_VALUE_NAME_1);
		$enumContains1->setDescription(self::ENUM_VALUE_DESC_1);
		$this->assertContains(
			$enumContains1,
			$interpretedValues,
			'',
			false,
			false
		);
		$enumContains2 = new EnumValueInterpretedType();
		$enumContains2->setName(self::ENUM_VALUE_NAME_2);
		$enumContains2->setDescription(self::ENUM_VALUE_DESC_2);
		$this->assertContains(
			$enumContains2,
			$interpretedValues,
			'',
			false,
			false
		);
		$enumContains3 = new EnumValueInterpretedType();
		$enumContains3->setName(self::ENUM_VALUE_NAME_3);
		$enumContains3->setDescription(self::ENUM_VALUE_DESC_3);
		$this->assertContains(
			$enumContains3,
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
		$retVal = $interpreter->generateType();

		$this->assertInstanceOf(EnumInterpretedType::class, $retVal);
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