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
	const INT_TYPE_NAME = 'Int';

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

	public function test_GivenAllNullableNoList_resolveFieldTypeDocComment_WontContainArrayTokens() {
		$typeUsage = $this->GivenAllNullableNoList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertNotContains("[]", $retVal);
	}

	public function test_GivenObjectNotNullableListNotNullable_resolveFieldTypeDocComment_WillContainArrayTokens() {
		$typeUsage = $this->GivenObjectNotNullableListNotNullable();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertContains("[]", $retVal);
	}

	public function test_GivenAllNullableNoList_resolveFieldTypeDocComment_WillContainNullToken() {
		$typeUsage = $this->GivenAllNullableNoList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertContains("null", $retVal);
	}

	public function test_GivenAllNullableInList_resolveFieldTypeDocComment_WillContainNullToken() {
		$typeUsage = $this->GivenAllNullableInList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertContains("null", $retVal);
	}

	public function test_GivenObjectNotNullableNoList_resolveFieldTypeDocComment_WontContainNullToken() {
		$typeUsage = $this->GivenObjectNotNullableNoList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertNotContains("null", $retVal);
	}

	public function test_GivenIntPrimaryTypeAllNullableNoList_resolveFieldTypeDocComment_WillConvertTypeName() {
		$typeUsage = $this->GivenIntPrimaryTypeAllNullableNoList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertContains('int', $retVal);
	}

	public function test_GivenNonPrimaryTypeAllNullableNoList_resolveFieldTypeDocComment_WillContainTypeName() {
		$typeUsage = $this->GivenNonPrimaryTypeAllNullableNoList();

		$retVal = $this->_typeFormatter->resolveFieldTypeDocComment($typeUsage);

		$this->assertContains(self::TYPE_NAME, $retVal);
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

	private function GivenAllNullableInList() {
		return new TypeUsage(
			self::TYPE_NAME,
			true,
			true,
			true
		);
	}

	private function GivenIntPrimaryTypeAllNullableNoList() {
		return new TypeUsage(
			self::INT_TYPE_NAME,
			true,
			false,
			true
		);
	}

	private function GivenNonPrimaryTypeAllNullableNoList() {
		return new TypeUsage(
			self::TYPE_NAME,
			true,
			false,
			true
		);
	}
}