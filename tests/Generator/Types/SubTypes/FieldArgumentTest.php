<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\FieldArgument;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class FieldArgumentTest extends \PHPUnit_Framework_TestCase {
	const FIELD_ARG_DEFAULT_VALUE = '123111';
	const FIELD_ARG_DESC = 'ADescription';
	const FIELD_ARG_NAME = 'AName';

	public function test_GivenFieldArgument_setDefaultValueThenGetDefaultValue_WillReturnDefaultValue() {
		$fieldArgument = $this->GivenFieldArgument();

		$fieldArgument->setDefaultValue(self::FIELD_ARG_DEFAULT_VALUE);
		$retVal = $fieldArgument->getDefaultValue();

		$this->assertEquals(self::FIELD_ARG_DEFAULT_VALUE, $retVal);
	}

	public function test_GivenFieldArgument_setDescriptionThenGetDescription_WillReturnDescription() {
		$fieldArgument = $this->GivenFieldArgument();

		$fieldArgument->setDescription(self::FIELD_ARG_DESC);
		$retVal = $fieldArgument->getDescription();

		$this->assertEquals(self::FIELD_ARG_DESC, $retVal);
	}

	public function test_GivenFieldArgument_setNameThenGetName_WillReturnName() {
		$fieldArgument = $this->GivenFieldArgument();

		$fieldArgument->setName(self::FIELD_ARG_NAME);
		$retVal = $fieldArgument->getName();

		$this->assertEquals(self::FIELD_ARG_NAME, $retVal);
	}

	public function test_GivenFieldArgument_setTypeThenGetType_WillReturnType() {
		$fieldArgument = $this->GivenFieldArgument();
		$givenType = $this->GivenType();

		$fieldArgument->setType($givenType);
		$retVal = $fieldArgument->getType();

		$this->assertEquals($givenType, $retVal);
	}

	private function GivenFieldArgument() {
		return new FieldArgument("", "", null, "");
	}

	private function GivenType() {
		return new TypeUsage("", false, false, false);
	}
}