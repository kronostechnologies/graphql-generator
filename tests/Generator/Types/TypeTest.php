<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\TypeFormatter;

class TypeTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'DeclaredType';
	const VALID_DESCRIPTION = 'This is a description test for the declared type.';
	const FIELD_NAME_1 = 'FirstField';
	const FIELD_DESCRIPTION_1 = 'Description for the first field';
	const FIELD_TYPE_NAME_1 = 'Int';
	const FIELD_NAME_2 = 'SecondField';
	const FIELD_TYPE_NAME_2 = 'Money';
	const INTERFACE_NAME = 'AnInterface';
	const FIELD_TYPE_PRIMARY = 'ID';
	const FIELD_TYPE_NON_PRIMARY = 'ANonPrimaryField';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatterMock;

	public function setUp() {
		$this->_stubFormatterMock = $this->createMock(StubFormatter::class);
	}

	public function test_GivenType_generateTypeDefinition_WillContainNameFragment() {
		$type = $this->GivenType();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenTypeWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$type = $this->GivenTypeWithDescriptionNoLineBreak();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenTypeWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$type = $this->GivenType();

		$retVal = $type->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenTypeWithInterface_generateTypeDefinition_WillContainInterfaceTag() {
		$type = $this->GivenTypeWithInterface();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'interfaces'", $retVal);
	}

	public function test_GivenTypeWithNoInterface_generateTypeDefinition_WontContainInterfaceTag() {
		$type = $this->GivenType();

		$retVal = $type->generateTypeDefinition();

		$this->assertNotContains("'interfaces'", $retVal);
	}

	public function test_GivenTypeWithFields_generateTypeDefinition_WillContainFieldsFragment() {
		$type = $this->GivenTypeWith2DistinctTypeFields();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenTypeWithFields_generateTypeDefinition_WillContainResolverFragment() {
		$type = $this->GivenTypeWith2DistinctTypeFields();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'resolver'", $retVal);
	}

	public function test_GivenTypeWithPrimaryField_generateTypeDefinition_WontContainResolverFragment() {
		$type = $this->GivenTypeWithPrimaryField();

		$retVal = $type->generateTypeDefinition();

		$this->assertNotContains("'resolver'", $retVal);
	}

	public function test_GivenTypeWithNonPrimaryField_generateTypeDefinition_WillContainResolverFragment() {
		$type = $this->GivenTypeWithNonPrimaryField();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'resolver'", $retVal);
	}

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

	public function test_GivenTypeWith2DistinctTypeFields_generateTypeDefinition_WillContainTypeFragment() {
		$type = $this->GivenTypeWith2DistinctTypeFields();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'type'", $retVal);
	}

	public function test_GivenType_generateTypeDefinition_WillContainName() {
		$type = $this->GivenType();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenTypeWithDescriptionNoLineBreak_generateTypeDefinition_WillFetchDescriptionValue() {
		$type = $this->GivenTypeWithDescriptionNoLineBreak();

		$this
			->_stubFormatterMock
			->expects($this->once())
			->method('standardizeDescription')
			->with(self::VALID_DESCRIPTION);

		$type->generateTypeDefinition();
	}

	public function test_GivenTypeWithInterface_getDependencies_WillContainInterface() {
		$type = $this->GivenTypeWithInterface();

		$retVal = $type->getDependencies();

		$this->assertContains(self::INTERFACE_NAME, $retVal);
	}

	public function test_GivenType_setInterfacesNamesThenGetInterfacesNames_WillReturnRightName() {
		$type = $this->GivenType();
		$givenInterfacesNames = $this->GivenInterfacesNames();

		$type->setInterfacesNames($givenInterfacesNames);
		$retVal = $type->getInterfacesNames();

		$this->assertEquals($givenInterfacesNames, $retVal);
	}

	public function test_GivenType_setFieldsThenGetFields_WillReturnRightFields() {
		$type = $this->GivenType();
		$givenFields = $this->GivenFields();

		$type->setFields($givenFields);
		$retVal = $type->getFields();

		$this->assertEquals($givenFields, $retVal);
	}

	protected function GivenType() {
		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
			[],
			[]
		);
	}

	protected function GivenTypeWithDescriptionNoLineBreak() {
		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
			[],
            [],
			self::VALID_DESCRIPTION
		);
	}

	protected function GivenTypeWithNoFields() {
		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
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
			$this->_stubFormatterMock,
			[$field1, $field2],
			[]
		);
	}

	protected function GivenTypeWithInterface() {
		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
			[],
			[self::INTERFACE_NAME]
		);
	}

	private function GivenTypeWithNonPrimaryField() {
		$field1 = new Field(
			self::FIELD_NAME_1,
			self::FIELD_DESCRIPTION_1,
			new TypeUsage(
				self::FIELD_TYPE_NON_PRIMARY,
				false,
				false,
				false
			),
			[]
		);

		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
			[$field1],
			[]
		);
	}

	private function GivenTypeWithPrimaryField() {
		$field1 = new Field(
			self::FIELD_NAME_1,
			self::FIELD_DESCRIPTION_1,
			new TypeUsage(
				self::FIELD_TYPE_PRIMARY,
				false,
				false,
				false
			),
			[]
		);

		return new Type(
			self::VALID_NAME,
			$this->_stubFormatterMock,
			[$field1],
			[]
		);
	}

	private function GivenInterfacesNames() {
		return [];
	}

	private function GivenFields() {
		return [];
	}
}