<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class TypeDeclarationFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const FIELD_NAME_1 = 'FirstField';
	const FIELD_NAME_2 = 'SecondField';
	const INTERFACE_NAME_1 = 'Iface1';
	const INTERFACE_NAME_2 = 'Iface2';
	const TYPE_DESC = 'Description of type';
	const TYPE_NAME = 'AName';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getFieldTypeDeclarationNonPrimaryType', 'getResolveFragment'])
			->getMock();
	}

	public function test_GivenTypeDeclarationWithoutDescription_generateTypeDefinition_WillContainName() {
		$type = $this->GivenTypeDeclarationWithoutDescription();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::TYPE_NAME, $retVal);
	}

	public function test_GivenTypeDeclarationWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$type = $this->GivenTypeDeclarationWithoutDescription();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenTypeDeclarationWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$type = $this->GivenTypeDeclarationWithDescription();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenTypeDeclarationWithoutFields_generateTypeDefinition_WillContainFieldsFragment() {
		$type = $this->GivenTypeDeclarationWithoutFields();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenTypeDeclarationWithFields_generateTypeDefinition_WillContainFieldsFragment() {
		$type = $this->GivenTypeDeclarationWithFields();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenTypeDeclarationWithoutInterfaces_generateTypeDefinition_WontContainInterfacesFragment() {
		$type = $this->GivenTypeDeclarationWithoutInterfaces();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'interfaces'", $retVal);
	}

	public function test_GivenTypeDeclarationWithInterfaces_generateTypeDefinition_WillContainInterfacesFragment() {
		$type = $this->GivenTypeDeclarationWithInterfaces();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'interfaces'", $retVal);
	}

	public function test_GivenTypeDeclarationWithoutFields_getDependencies_WillBeEmpty() {
		$type = $this->GivenTypeDeclarationWithoutFields();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeDeclarationWithoutInterfaces_getDependencies_WillBeEmpty() {
		$type = $this->GivenTypeDeclarationWithoutInterfaces();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeDeclarationWithFields_getDependencies_WontBeEmpty() {
		$type = $this->GivenTypeDeclarationWithFields();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenTypeDeclarationWithInterfaces_getDependencies_WontBeEmpty() {
		$type = $this->GivenTypeDeclarationWithInterfaces();
		$generator = $this->GivenTypeDeclarationGenerator($type);

		$retVal = $generator->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	protected function GivenTypeDeclarationWithoutDescription() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationWithoutFields() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationWithoutInterfaces() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationWithInterfaces() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);
		$typeDeclaration->setInterfacesNames([self::INTERFACE_NAME_1, self::INTERFACE_NAME_2]);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationWithDescription() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);
		$typeDeclaration->setDescription(self::TYPE_DESC);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationWithFields() {
		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME_1);
		$field1->setFieldType(new TypeUsageInterpretedType());

		$field2 = new FieldInterpretedType();
		$field2->setName(self::FIELD_NAME_2);
		$field2->setFieldType(new TypeUsageInterpretedType());

		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_NAME);
		$typeDeclaration->setFields([$field1, $field2]);

		return $typeDeclaration;
	}

	protected function GivenTypeDeclarationGenerator($field) {
		$generator = new TypeDeclarationFragmentGenerator();
		$generator->setTypeDeclaration($field);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}