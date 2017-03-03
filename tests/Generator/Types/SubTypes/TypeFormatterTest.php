<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\BaseTypeFormatter;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class TypeFormatterTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'TypeTest';

	const ALL_NULLABLE_NO_LIST = 'TypeTest';
	const OBJ_NOT_NULLABLE_NO_LIST = 'Type::nonNull(TypeTest)';
	const OBJ_NOT_NULLABLE_WITH_LIST = 'Type::listOf(Type::nonNull(TypeTest))';
	const OBJ_LIST_NOT_NULLABLE = 'Type::nonNull(Type::listOf(Type::nonNull(TypeTest)))';

	/**
	 * @var BaseTypeFormatter
	 */
	protected $_typeFormatter;

	public function setUp() {
		$this->_typeFormatter = new BaseTypeFormatter();
	}

	public function test_GivenAllNullableNoList_getFieldTypeDeclaration_WillReturnRightString() {
		$typeUsage = $this->GivenAllNullableNoList();

		$retVal = $this->_typeFormatter->getFieldTypeDeclaration($typeUsage);

		$this->assertEquals(self::ALL_NULLABLE_NO_LIST, $retVal);
	}

	public function test_GivenObjectNotNullableNoList_getFieldTypeDeclaration_WillReturnRightString() {
		$typeUsage = $this->GivenObjectNotNullableNoList();

		$retVal = $this->_typeFormatter->getFieldTypeDeclaration($typeUsage);

		$this->assertEquals(self::OBJ_NOT_NULLABLE_NO_LIST, $retVal);
	}

	public function test_GivenObjectNotNullableNullableList_getFieldTypeDeclaration_WillReturnRightString() {
		$typeUsage = $this->GivenObjectNotNullableNullableList();

		$retVal = $this->_typeFormatter->getFieldTypeDeclaration($typeUsage);

		$this->assertEquals(self::OBJ_NOT_NULLABLE_WITH_LIST, $retVal);
	}

	public function test_GivenObjectNotNullableListNotNullable_getFieldTypeDeclaration_WillReturnRightString() {
		$typeUsage = $this->GivenObjectNotNullableListNotNullable();

		$retVal = $this->_typeFormatter->getFieldTypeDeclaration($typeUsage);

		$this->assertEquals(self::OBJ_LIST_NOT_NULLABLE, $retVal);
	}

	protected function GivenAllNullableNoList() {
		return new TypeUsage(
			self::TYPE_NAME,
			true,
			false,
			true
		);
	}

	protected function GivenObjectNotNullableNoList() {
		return new TypeUsage(
			self::TYPE_NAME,
			false,
			false,
			true
		);
	}

	protected function GivenObjectNotNullableNullableList() {
		return new TypeUsage(
			self::TYPE_NAME,
			false,
			true,
			true
		);
	}

	protected function GivenObjectNotNullableListNotNullable() {
		return new TypeUsage(
			self::TYPE_NAME,
			false,
			true,
			false
		);
	}
}