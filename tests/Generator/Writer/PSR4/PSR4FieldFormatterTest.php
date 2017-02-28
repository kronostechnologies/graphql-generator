<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Writer\PSR4\PSR4FieldDeclaration;

class PSR4FieldFormatterTest extends \PHPUnit_Framework_TestCase {
	const FIELD_NAME = "AFieldName";
	const PRIMARY_FIELD_TYPE_OLD = "ID";
	const PRIMARY_FIELD_TYPE_NEW = "int";
	const TYPE_NAME = "ATypeName";

	public function test_GivenNullableField_getAnnotationString_ContainsNullFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getAnnotationString();

		$this->assertContains("|null", $retVal);
	}

	public function test_GivenNonNullableField_getAnnotationString_DoesNotContainNullFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNonNullableField(), true);

		$retVal = $fieldDeclaration->getAnnotationString();

		$this->assertNotContains("|null", $retVal);
	}

	public function test_GivenNullableListField_getAnnotationString_ContainsArrayFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNullableListField(), true);

		$retVal = $fieldDeclaration->getAnnotationString();

		$this->assertContains("[]", $retVal);
	}

	public function test_GivenNullableField_getVariableString_ContainsFieldNameFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getVariableString();

		$this->assertContains(self::FIELD_NAME, $retVal);
	}

	public function test_GivenNullableFieldWithAnnotationsOn_getFieldDeclaration_ContainsAnnotationFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNullableField(), true);

		$retVal = $fieldDeclaration->getFieldDeclaration();

		$this->assertContains("@var", $retVal);
	}

	public function test_GivenNullableFieldWithAnnotationsOff_getFieldDeclaration_DoesNotContainAnottationFragment() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenNullableField(), false);

		$retVal = $fieldDeclaration->getFieldDeclaration();

		$this->assertNotContains("@var", $retVal);
	}

	public function test_GivenPrimaryTypedField_getAnnotationString_WillContainReplacementType() {
		$fieldDeclaration = new PSR4FieldDeclaration($this->GivenPrimaryTypedField(), true);

		$retVal = $fieldDeclaration->getFieldDeclaration();

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