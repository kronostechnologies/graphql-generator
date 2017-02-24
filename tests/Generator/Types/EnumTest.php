<?php


namespace GraphQLGen\Tests\Generator\Types;


class EnumTest extends \PHPUnit_Framework_TestCase {
	public function test_GivenEnum_getName_WillReturnName() {

	}

	public function test_GivenEnum_getDependencies_WillBeEmpty() {

	}

	public function test_GivenEnumWithNoValues_getConstants_WillBeEmpty() {

	}

	public function test_GivenEnumWith3Values_getConstants_WillContain3Const() {

	}

	public function test_GivenEnumWith3ConstantsAndNoContantsFormatter_generateTypeDefinition_WontReturnSelfReferences() {

	}

	public function test_GivenEnumWith3ConstantsAndConstantsFormatter_generateTypeDefinition_WillReturnSelfReferences() {

	}

	public function test_GivenEnum_generateTypeDefinition_WillContainName() {

	}

	public function test_GivenEnumWithDescriptionNoLineJump_generateTypeDefinition_WillContainDescription() {

	}
}