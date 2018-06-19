<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Main;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class InputFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const INPUT_DESC = 'A Short Description';
	const INPUT_NAME = 'AnInput';

	const INPUT_FIELD_1_TYPE = 'FirstFieldType';
	const INPUT_FIELD_1_NAME = 'FirstField';

	const INPUT_FIELD_2_TYPE = 'SecondFieldType';
	const INPUT_FIELD_2_NAME = 'SecondField';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getResolveFragment', 'canInterpretedTypeSkipResolver'])
			->getMock();
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WillContainNameFragment() {
		$input = $this->GivenInputWithoutDescription();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenInputWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$input = $this->GivenInputWithDescription();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$input = $this->GivenInputWithoutDescription();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenInputWithFields_generateTypeDefinition_WillContainFieldsFragment() {
		$input = $this->GivenInputWithFields();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WillContainNameValue() {
		$input = $this->GivenInputWithoutDescription();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::INPUT_NAME, $retVal);
	}

	public function test_GivenInputWithNoFields_getDependencies_WillBeEmpty() {
		$input = $this->GivenInputWithoutDescription();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenInputWithFields_getDependencies_WontBeEmpty() {
		$input = $this->GivenInputWithFields();
		$generator = $this->GivenInputGenerator($input);

		$retVal = $generator->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	private function GivenInputGenerator($input) {
		$generator = new InputFragmentGenerator();
		$generator->setInputType($input);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}

	private function GivenInputWithDescription() {
		$inputType = new InputInterpretedType();
		$inputType->setName(self::INPUT_NAME);
		$inputType->setDescription(self::INPUT_DESC);

		return $inputType;
	}

	private function GivenInputWithFields() {
		$field1 = new InputFieldInterpretedType();
		$field1->setName(self::INPUT_FIELD_1_NAME);
		$field1->setFieldType(new TypeUsageInterpretedType());

		$field2 = new InputFieldInterpretedType();
		$field2->setName(self::INPUT_FIELD_2_NAME);
		$field2->setFieldType(new TypeUsageInterpretedType());

		$inputType = new InputInterpretedType();
		$inputType->setName(self::INPUT_NAME);
		$inputType->setFields([$field1, $field2]);

		return $inputType;
	}

	private function GivenInputWithoutDescription() {
		$inputType = new InputInterpretedType();
		$inputType->setName(self::INPUT_NAME);

		return $inputType;
	}



}