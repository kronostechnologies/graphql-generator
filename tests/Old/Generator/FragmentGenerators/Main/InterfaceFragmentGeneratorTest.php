<?php


namespace GraphQLGen\Tests\Old\Generator\FragmentGenerators\Main;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class InterfaceFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'AnInterface';
	const VALID_DESCRIPTION = 'Description of the interface';

	const FIELD_NAME_1 = 'SimilarField1';
	const FIELD_NAME_2 = 'SimilarField2';

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getResolveFragment', 'getInterfaceResolveFragment', 'canInterpretedTypeSkipResolver'])
			->getMock();
	}

	public function test_GivenInterfaceWithoutDescription_generateTypeDefinition_WillContainName() {
		$interface = $this->GivenInterfaceWithoutDescription();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenInterfaceWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$interface = $this->GivenInterfaceWithoutDescription();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenInterfaceWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$interface = $this->GivenInterfaceWithDescription();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenInterfaceWithoutDescription_generateTypeDefinition_WillContainFieldsFragment() {
		$interface = $this->GivenInterfaceWithoutDescription();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenInterfaceWithFields_generateTypeDefinition_WillContainFieldsFragment() {
		$interface = $this->GivenInterfaceWithFields();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'fields'", $retVal);
	}

	public function test_GivenInterfaceWithoutDescription_generateTypeDefinition_WillContainResolveType() {
		$interface = $this->GivenInterfaceWithoutDescription();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'resolveType'", $retVal);
	}


	public function test_GivenInterfaceWithoutFields_getDependencies_WillBeEmpty() {
		$interface = $this->GivenInterfaceWithoutFields();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenInterfaceWithFields_getDependencies_WontBeEmpty() {
		$interface = $this->GivenInterfaceWithFields();
		$generator = $this->GivenInterfaceGenerator($interface);

		$retVal = $generator->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	protected function GivenInterfaceWithoutDescription() {
		$interface = new InterfaceDeclarationInterpretedType();
		$interface->setName(self::VALID_NAME);

		return $interface;
	}

	protected function GivenInterfaceWithDescription() {
		$interface = new InterfaceDeclarationInterpretedType();
		$interface->setName(self::VALID_NAME);
		$interface->setDescription(self::VALID_DESCRIPTION);

		return $interface;
	}

	protected function GivenInterfaceWithFields() {
		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME_1);
		$field1->setFieldType(new TypeUsageInterpretedType());

		$field2 = new FieldInterpretedType();
		$field2->setName(self::FIELD_NAME_2);
		$field2->setFieldType(new TypeUsageInterpretedType());

		$interface = new InterfaceDeclarationInterpretedType();
		$interface->setName(self::VALID_NAME);
		$interface->setFields([$field1, $field2]);

		return $interface;
	}

	protected function GivenInterfaceWithoutFields() {
		$interface = new InterfaceDeclarationInterpretedType();
		$interface->setName(self::VALID_NAME);

		return $interface;
	}

	protected function GivenInterfaceGenerator($interface) {
		$generator = new InterfaceFragmentGenerator();
		$generator->setInterfaceType($interface);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}