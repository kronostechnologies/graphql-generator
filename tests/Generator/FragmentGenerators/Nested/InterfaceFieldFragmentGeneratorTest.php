<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Nested\InterfaceFieldFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class InterfaceFieldFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const FIELD_DESCRIPTION = 'Description for the field';
	const FIELD_NAME = 'ASingleField';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getResolveFragment', 'isScalarOrEnumType'])
			->getMock();
	}

	public function test_GivenInterfaceFieldWithoutDescription_generateTypeDefinition_WillContainName() {
		$field = $this->GivenInterfaceFieldWithoutDescription();
		$generator = $this->GivenInterfaceFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::FIELD_NAME, $retVal);
	}

	public function test_GivenInterfaceFieldWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$field = $this->GivenInterfaceFieldWithoutDescription();
		$generator = $this->GivenInterfaceFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenInterfaceFieldWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$field = $this->GivenInterfaceFieldWithDescription();
		$generator = $this->GivenInterfaceFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenInterfaceFieldWithoutDescription_generateTypeDefinition_WillContainTypeFragment() {
		$field = $this->GivenInterfaceFieldWithoutDescription();
		$generator = $this->GivenInterfaceFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'type'", $retVal);
	}

	protected function GivenInterfaceFieldWithoutDescription() {
		$field = new FieldInterpretedType();
		$field->setName(self::FIELD_NAME);
		$field->setFieldType(new TypeUsageInterpretedType());

		return $field;
	}

	protected function GivenInterfaceFieldWithDescription() {
		$field = new FieldInterpretedType();
		$field->setName(self::FIELD_NAME);
		$field->setDescription(self::FIELD_DESCRIPTION);
		$field->setFieldType(new TypeUsageInterpretedType());

		return $field;
	}

	protected function GivenInterfaceFieldGenerator($field) {
		$generator = new InterfaceFieldFragmentGenerator();
		$generator->setInterfaceFieldType($field);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}