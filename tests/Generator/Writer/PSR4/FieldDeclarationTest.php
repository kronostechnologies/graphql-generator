<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Writer\PSR4\FieldDeclaration;

class FieldDeclarationTest extends \PHPUnit_Framework_TestCase {
	const FIELD_NAME = "AFieldName";
	const PRIMARY_FIELD_TYPE_OLD = "ID";
	const PRIMARY_FIELD_TYPE_NEW = "int";
	const TYPE_NAME = "ATypeName";

	public function test_GivenNullableField_getFieldDeclarationVariable_ContainsNullFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertContains("|null", $retVal);
	}

	public function test_GivenNonNullableField_getFieldDeclarationVariable_DoesNotContainNullFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNonNullableField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertNotContains("|null", $retVal);
	}

	public function test_GivenNullableListField_getFieldDeclarationVariable_ContainsArrayFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNullableListField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertContains("[]", $retVal);
	}

	public function test_GivenNullableField_getVariableString_ContainsFieldNameFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertContains(self::FIELD_NAME, $retVal);
	}

	public function test_GivenNullableFieldWithAnnotationsOn_getFieldDeclarationVariable_ContainsAnnotationFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertContains("@var", $retVal);
	}

	public function test_GivenNullableFieldWithAnnotationsOff_getFieldDeclaration_DoesNotContainAnottationFragment() {
		$fieldDeclaration = new FieldDeclaration($this->GivenNullableField(), false);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertNotContains("@var", $retVal);
	}

	public function test_GivenPrimaryTypedField_getFieldDeclarationVariable_WillContainReplacementType() {
		$fieldDeclaration = new FieldDeclaration($this->GivenPrimaryTypedField(), true);

		$retVal = $fieldDeclaration->getFieldDeclarationVariable();

		$this->assertContains(self::PRIMARY_FIELD_TYPE_NEW, $retVal);
	}

	protected function GivenNullableField() {
		return new Field(
			self::FIELD_NAME,
			null,
			new TypeUsage(
				self::TYPE_NAME,
				true,
				false,
				false
			),
			[]
		);
	}

	protected function GivenNonNullableField() {
		return new Field(
			self::FIELD_NAME,
			null,
			new TypeUsage(
				self::TYPE_NAME,
				false,
				false,
				false
			),
			[]
		);
	}

	protected function GivenNullableListField() {
		return new Field(
			self::FIELD_NAME,
			null,
			new TypeUsage(
				self::TYPE_NAME,
				true,
				true,
				true
			),
			[]
		);
	}

	protected function GivenPrimaryTypedField() {
		return new Field(
			self::FIELD_NAME,
			null,
			new TypeUsage(
				self::PRIMARY_FIELD_TYPE_OLD,
				true,
				false,
				false
			),
			[]
		);
	}
}