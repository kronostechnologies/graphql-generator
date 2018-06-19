<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Main;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;

class ScalarFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'ScalarDate';
	const VALID_DESCRIPTION = 'This is a description for the scalar date type.';

	public function test_GivenScalar_generateTypeDefinition_WillContainName() {
		$scalar = $this->GivenScalar();
		$generator = $this->GivenScalarGenerator($scalar);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenScalarWithDescription_generateTypeDefinition_WillContainDescription() {
		$scalar = $this->GivenScalarWithDescription();
		$generator = $this->GivenScalarGenerator($scalar);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	public function test_GivenScalar_generateTypeDefinition_WillContainNameFragment() {
		$scalar = $this->GivenScalar();
		$generator = $this->GivenScalarGenerator($scalar);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("\$this->name", $retVal);
	}

	public function test_GivenScalarWithDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$scalar = $this->GivenScalar();
		$generator = $this->GivenScalarGenerator($scalar);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("\$this->description", $retVal);
	}

	public function test_GivenScalarWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$scalar = $this->GivenScalarWithDescription();
		$generator = $this->GivenScalarGenerator($scalar);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("\$this->description", $retVal);
	}

	protected function GivenScalar() {
		$scalar = new ScalarInterpretedType();
		$scalar->setName(self::VALID_NAME);

		return $scalar;
	}

	protected function GivenScalarWithDescription() {
		$scalar = new ScalarInterpretedType();
		$scalar->setName(self::VALID_NAME);
		$scalar->setDescription(self::VALID_DESCRIPTION);

		return $scalar;
	}

	protected function GivenScalarGenerator($scalar) {
		$generator = new ScalarFragmentGenerator();
		$generator->setScalarType($scalar);
		$generator->setFormatter(new StubFormatter());

		return $generator;
	}
}