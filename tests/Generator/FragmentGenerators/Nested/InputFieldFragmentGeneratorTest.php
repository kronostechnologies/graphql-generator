<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Nested;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Nested\InputFieldFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class InputFieldFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const INPUT_FIELD_DESC = 'Description of an input';
	const INPUT_FIELD_NAME = 'InputFieldName';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getResolveFragment', 'canInterpretedTypeSkipResolver'])
			->getMock();
	}

	public function test_GivenInputFieldWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$inputField = $this->GivenInputFieldWithoutDescription();
		$generator = $this->GivenInputFieldGenerator($inputField);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenInputFieldWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$inputField = $this->GivenInputFieldWithDescription();
		$generator = $this->GivenInputFieldGenerator($inputField);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenInputFieldWithoutDescription_generateTypeDefinition_WillContainTypeFragment() {
		$inputField = $this->GivenInputFieldWithoutDescription();
		$generator = $this->GivenInputFieldGenerator($inputField);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'type'", $retVal);
	}

	public function test_GivenInputFieldWithoutDescription_generateTypeDefinition_WillContainName() {
		$inputField = $this->GivenInputFieldWithoutDescription();
		$generator = $this->GivenInputFieldGenerator($inputField);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::INPUT_FIELD_NAME, $retVal);
	}

	protected function GivenInputFieldWithoutDescription() {
		$inputField = new InputFieldInterpretedType();
		$inputField->setName(self::INPUT_FIELD_NAME);
		$inputField->setFieldType(new TypeUsageInterpretedType());

		return $inputField;
	}

	protected function GivenInputFieldWithDescription() {
		$inputField = new InputFieldInterpretedType();
		$inputField->setName(self::INPUT_FIELD_NAME);
		$inputField->setDescription(self::INPUT_FIELD_DESC);
		$inputField->setFieldType(new TypeUsageInterpretedType());

		return $inputField;
	}

	protected function GivenInputFieldGenerator($inputField) {
		$generator = new InputFieldFragmentGenerator();
		$generator->setInputFieldType($inputField);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}