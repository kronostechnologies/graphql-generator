<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class FieldTest extends \PHPUnit_Framework_TestCase {
	const FIELD_NAME = 'AFieldName';
	const FIELD_DESC = 'AFieldDesc';

	public function test_GivenField_setNameThenGetName_WillReturnName() {
		$field = $this->GivenField();

		$field->setName(self::FIELD_NAME);
		$retVal = $field->getName();

		$this->assertEquals(self::FIELD_NAME, $retVal);
	}

	public function test_GivenField_setDescriptionThenGetDescription_WillReturnDescription() {
		$field = $this->GivenField();

		$field->setDescription(self::FIELD_DESC);
		$retVal = $field->getDescription();

		$this->assertEquals(self::FIELD_DESC, $retVal);
	}

	public function test_GivenField_setFieldTypeThenGetFieldType_WillReturnFieldType() {
		$field = $this->GivenField();
		$givenFieldType = $this->GivenFieldType();

		$field->setFieldType($givenFieldType);
		$retVal = $field->getFieldType();

		$this->assertEquals($givenFieldType, $retVal);
	}

	public function test_GivenField_setArgumentsThenGetArguments_WillReturnArguments() {
		$field = $this->GivenField();
		$givenArguments = $this->GivenArguments();

		$field->setArguments($givenArguments);
		$retVal = $field->getArguments();

		$this->assertEquals($givenArguments, $retVal);
	}

	private function GivenField() {
		return new Field("", null, null, []);
	}

	private function GivenFieldType() {
		return new TypeUsage("", false, false, false);
	}

	private function GivenArguments() {
		return ["AnArgument"];
	}
}