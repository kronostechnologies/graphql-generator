<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Nested;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Nested\TypeDeclarationFieldFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldArgumentInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class TypeDeclarationFieldFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const ARG_1_NAME = 'FirstArgument';
	const ARG_2_NAME = 'SecondArgument';
	const FIELD_DESC = 'Description of the field';
	const FIELD_NAME = 'AField';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getFieldTypeDeclarationNonPrimaryType', 'getResolveFragment', 'isScalarOrEnumType'])
			->getMock();
	}

	public function test_GivenTypeUsageWithoutDescription_generateTypeDefinition_WillContainName() {
		$field = $this->GivenTypeUsageWithoutDescription();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::FIELD_NAME, $retVal);
	}

	public function test_GivenTypeUsageWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$field = $this->GivenTypeUsageWithoutDescription();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenTypeUsageWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$field = $this->GivenTypeUsageWithDescription();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenTypeUsageWithDescription_generateTypeDefinition_WillFetchResolver() {
		$field = $this->GivenTypeUsageWithDescription();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$this->_stubFormatter->expects($this->once())->method("getResolveFragment");

		$generator->generateTypeDefinition();
	}

	public function test_GivenTypeUsageWithoutArgs_generateTypeDefinition_WontContainArgsFragment() {
		$field = $this->GivenTypeUsageWithoutArgs();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'args'", $retVal);
	}

	public function test_GivenTypeUsageWithArgs_generateTypeDefinition_WillContainArgsFragment() {
		$field = $this->GivenTypeUsageWithArgs();
		$generator = $this->GivenTypeDeclarationFieldGenerator($field);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'args'", $retVal);
	}

	protected function GivenTypeUsageWithoutDescription() {
		$typeUsage = new FieldInterpretedType();
		$typeUsage->setName(self::FIELD_NAME);
		$typeUsage->setFieldType(new TypeUsageInterpretedType());

		return $typeUsage;
	}

	protected function GivenTypeUsageWithDescription() {
		$typeUsage = new FieldInterpretedType();
		$typeUsage->setName(self::FIELD_NAME);
		$typeUsage->setDescription(self::FIELD_DESC);
		$typeUsage->setFieldType(new TypeUsageInterpretedType());

		return $typeUsage;
	}

	protected function GivenTypeUsageWithoutArgs() {
		$typeUsage = new FieldInterpretedType();
		$typeUsage->setName(self::FIELD_NAME);
		$typeUsage->setFieldType(new TypeUsageInterpretedType());

		return $typeUsage;
	}

	protected function GivenTypeUsageWithArgs() {
		$arg1 = new FieldArgumentInterpretedType();
		$arg1->setName(self::ARG_1_NAME);
		$arg1->setFieldType(new TypeUsageInterpretedType());

		$arg2 = new FieldArgumentInterpretedType();
		$arg2->setName(self::ARG_2_NAME);
		$arg2->setFieldType(new TypeUsageInterpretedType());

		$typeUsage = new FieldInterpretedType();
		$typeUsage->setName(self::FIELD_NAME);
		$typeUsage->setArguments([$arg1, $arg2]);
		$typeUsage->setFieldType(new TypeUsageInterpretedType());

		return $typeUsage;
	}

	protected function GivenTypeDeclarationFieldGenerator($typeUsage) {
		$generator = new TypeDeclarationFieldFragmentGenerator();
		$generator->setTypeDeclarationFieldType($typeUsage);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}