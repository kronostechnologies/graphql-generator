<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;

class TypeTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'DeclaredType';
	const VALID_DESCRIPTION = 'This is a description test for the declared type.';
	const FIELD_NAME_1 = 'FirstField';
	const FIELD_DESCRIPTION_1 = 'Description for the first field';
	const FIELD_TYPE_NAME_1 = 'Int';
	const FIELD_NAME_2 = 'SecondField';
	const FIELD_TYPE_NAME_2 = 'Money';

	public function test_GivenType_getName_WillReturnName() {
		$type = $this->GivenType();

		$retVal = $type->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenType_getConstantsDeclaration_WillReturnNull() {
		$type = $this->GivenType();

		$retVal = $type->getVariablesDeclarations();

		$this->assertNull($retVal);
	}

	public function test_GivenTypeWithNoFields_getDependencies_WillBeEmpty() {
		$type = $this->GivenTypeWithNoFields();

		$retVal = $type->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeWith2DistinctTypeFields_getDependencies_WillContain2Types() {
		$type = $this->GivenTypeWith2DistinctTypeFields();

		$retVal = $type->getDependencies();

		$this->assertCount(2, $retVal);
	}

	public function test_GivenTypeWith2DistinctTypeFields_getDependencies_WillContain2DistinctTypes() {
		$type = $this->GivenTypeWith2DistinctTypeFields();

		$retVal = $type->getDependencies();

		$this->assertNotEquals($retVal[0], $retVal[1]);
	}

	public function test_GivenType_generateTypeDefinition_WillContainName() {
		$type = $this->GivenType();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenTypeWithDescriptionNoLineBreak_generateTypeDefinition_WillContainDescription() {
		$type = $this->GivenTypeWithDescriptionNoLineBreak();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	protected function GivenType() {
		return new Type(
			self::VALID_NAME,
			new StubFormatter(),
			[],
			[]
		);
	}

	protected function GivenTypeWithDescriptionNoLineBreak() {
		return new Type(
			self::VALID_NAME,
			new StubFormatter(),
			[],
            [],
			self::VALID_DESCRIPTION
		);
	}

	protected function GivenTypeWithNoFields() {
		return new Type(
			self::VALID_NAME,
			new StubFormatter(),
			[],
            []
		);
	}

	protected function GivenTypeWith2DistinctTypeFields() {
		$field1 = new Field(
			self::FIELD_NAME_1,
			self::FIELD_DESCRIPTION_1,
			new TypeUsage(
				self::FIELD_TYPE_NAME_1,
				false,
				false,
				false
			),
			[]
		);

		$field2 = new Field(
			self::FIELD_NAME_2,
			null,
			new TypeUsage(
				self::FIELD_TYPE_NAME_2,
				false,
				false,
				false
			),
			[]
		);

		return new Type(
			self::VALID_NAME,
			new StubFormatter(),
			[$field1, $field2],
			[]
		);
	}
}