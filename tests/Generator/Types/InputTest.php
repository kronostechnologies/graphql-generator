<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Input;
use GraphQLGen\Generator\Types\SubTypes\InputField;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class InputTest extends \PHPUnit_Framework_TestCase {
	const INPUT_DESC = 'A Short Description';
	const INPUT_NAME = 'AnInput';

	const INPUT_FIELD_1_TYPE = 'FirstFieldType';
	const INPUT_FIELD_1_NAME = 'FirstField';

	const INPUT_FIELD_2_TYPE = 'SecondFieldType';
	const INPUT_FIELD_2_NAME = 'SecondField';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatterMock;

	public function setUp() {
		$this->_stubFormatterMock = $this->createMock(StubFormatter::class);
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WillContainNameFragment() {
		$input = $this->GivenInputWithoutDescription();

		$retVal = $input->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WillContainNameValue() {
		$input = $this->GivenInputWithoutDescription();

		$retVal = $input->generateTypeDefinition();

		$this->assertContains(self::INPUT_NAME, $retVal);
	}

	public function test_GivenInputWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$input = $this->GivenInputWithDescription();

		$retVal = $input->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenInputWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$input = $this->GivenInputWithoutDescription();

		$retVal = $input->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenInputWithFields_generateTypeDefinition_WillContainFieldsFragment() {
		$input = $this->GivenInputWithFields();

		$retVal = $input->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenInputWithNoFields_getDependendies_WillBeEmpty() {
		$input = $this->GivenInputWithoutDescription();

		$retVal = $input->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenInputWithFields_getDependendies_WillContainField1Type() {
		$input = $this->GivenInputWithFields();

		$retVal = $input->getDependencies();

		$this->assertContains(self::INPUT_FIELD_1_TYPE, $retVal);
	}

	public function test_GivenInputWithFields_getDependendies_WillContainField2Type() {
		$input = $this->GivenInputWithFields();

		$retVal = $input->getDependencies();

		$this->assertContains(self::INPUT_FIELD_2_TYPE, $retVal);
	}

	protected function GivenInputWithoutDescription() {
		return new Input(self::INPUT_NAME, $this->_stubFormatterMock, []);
	}

	protected function GivenInputWithDescription() {
		return new Input(self::INPUT_NAME, $this->_stubFormatterMock, [], self::INPUT_DESC);
	}

	protected function GivenInputWithFields() {
		$field1 = new InputField(self::INPUT_FIELD_1_NAME, null, new TypeUsage(self::INPUT_FIELD_1_TYPE, false, false, false));

		$field2 = new InputField(self::INPUT_FIELD_2_NAME, null, new TypeUsage(self::INPUT_FIELD_2_TYPE, false, false, false));

		return new Input(self::INPUT_NAME, $this->_stubFormatterMock, [$field1, $field2], self::INPUT_DESC);
	}
}