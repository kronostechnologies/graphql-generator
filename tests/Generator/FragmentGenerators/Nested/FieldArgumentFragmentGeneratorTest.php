<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Nested\FieldArgumentFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class FieldArgumentFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const ARG_DEFAULT_VALUE = '123123123';
	const ARG_NAME = 'AnArgument';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getFieldTypeDeclarationNonPrimaryType', 'getResolveFragment', 'canInterpretedTypeSkipResolver'])
			->getMock();
	}

	public function test_GivenFieldArgumentWithoutDefaultValue_generateTypeDefinition_WillContainName() {
		$argument = $this->GivenFieldArgumentWithoutDefaultValue();
		$generator = $this->GivenFieldArgumentGenerator($argument);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::ARG_NAME, $retVal);
	}

	public function test_GivenFieldArgumentWithoutDefaultValue_generateTypeDefinition_WontContainDefaultValueFragment() {
		$argument = $this->GivenFieldArgumentWithoutDefaultValue();
		$generator = $this->GivenFieldArgumentGenerator($argument);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'defaultValue'", $retVal);
	}

	public function test_GivenFieldArgumentWithDefaultValue_generateTypeDefinition_WillContainDefaultValueFragment() {
		$argument = $this->GivenFieldArgumentWithDefaultValue();
		$generator = $this->GivenFieldArgumentGenerator($argument);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'defaultValue'", $retVal);
	}

	public function test_GivenFieldArgumentWithoutDefaultValue_generateTypeDefinition_WillContainTypeFragment() {
		$argument = $this->GivenFieldArgumentWithoutDefaultValue();
		$generator = $this->GivenFieldArgumentGenerator($argument);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'type'", $retVal);
	}

	protected function GivenFieldArgumentWithoutDefaultValue() {
		$argument = new FieldArgumentInterpretedType();
		$argument->setName(self::ARG_NAME);
		$argument->setFieldType(new TypeUsageInterpretedType());

		return $argument;
	}

	protected function GivenFieldArgumentWithDefaultValue() {
		$argument = new FieldArgumentInterpretedType();
		$argument->setName(self::ARG_NAME);
		$argument->setDefaultValue(self::ARG_DEFAULT_VALUE);
		$argument->setFieldType(new TypeUsageInterpretedType());

		return $argument;
	}

	protected function GivenFieldArgumentGenerator($argument) {
		$generator = new FieldArgumentFragmentGenerator();
		$generator->setFieldArgumentType($argument);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}