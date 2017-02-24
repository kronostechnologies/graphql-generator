<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Scalar;

class ScalarTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'ScalarDate';
	const VALID_DESCRIPTION = 'This is a description for the scalar date type.';

	public function test_GivenScalar_getName_WillReturnName() {
		$scalar = $this->GivenScalar();

		$retVal = $scalar->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenScalar_generateTypeDefinition_WillContainName() {
		$scalar = $this->GivenScalar();

		$retVal = $scalar->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenScalarWithDescription_generateTypeDefinition_WillContainDescription() {
		$scalar = $this->GivenScalarWithDescription();

		$retVal = $scalar->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	public function test_GivenScalar_getDependencies_WillBeEmpty() {
		$scalar = $this->GivenScalar();

		$retVal = $scalar->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenScalar_getConstantsDeclaration_WillReturnNull() {
		$scalar = $this->GivenScalar();

		$retVal = $scalar->getConstantsDeclaration();

		$this->assertNull($retVal);
	}

	protected function GivenScalar() {
		return new Scalar(
			self::VALID_NAME,
			new StubFormatter(),
			null
		);
	}

	protected function GivenScalarWithDescription() {
		return new Scalar(
			self::VALID_NAME,
			new StubFormatter(),
			self::VALID_DESCRIPTION
		);
	}
}