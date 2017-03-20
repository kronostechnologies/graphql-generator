<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Tests\Mocks\InvalidGeneratorType;

class BaseTypeGeneratorTest extends \PHPUnit_Framework_TestCase {
	const VALID_DESCRIPTION = 'A Generator Description';
	const VALID_NAME = 'A Generator Name';

	public function test_GivenStandardTypeGenerator_setDescriptionThenGetDescription_WillChangeDescription() {
		$generator = $this->GivenStandardTypeGenerator();

		$generator->setDescription(self::VALID_DESCRIPTION);
		$retVal = $generator->getDescription();

		$this->assertEquals(self::VALID_DESCRIPTION, $retVal);
	}

	public function test_GivenStandardTypeGenerator_setNameThenGetName_WillChangeName() {
		$generator = $this->GivenStandardTypeGenerator();

		$generator->setName(self::VALID_NAME);
		$retVal = $generator->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenStandardTypeGenerator_setFormatterThenGetFormatter_WillChangeFormatter() {
		$generator = $this->GivenStandardTypeGenerator();
		$givenFormatter = $this->GivenFormatter();

		$generator->setFormatter($givenFormatter);
		$retVal = $generator->getFormatter();

		$this->assertEquals($givenFormatter, $retVal);
	}

	protected function GivenStandardTypeGenerator() {
		return new InvalidGeneratorType();
	}

	protected function GivenFormatter() {
		return new StubFormatter();
	}
}