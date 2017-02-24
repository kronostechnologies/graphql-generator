<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class InterfaceDeclarationTest extends \PHPUnit_Framework_TestCase {
	const VALID_NAME = 'AnInterface';
	const FIELD_NAME_1 = 'SimilarField1';
	const FIELD_NAME_2 = 'SimilarField2';
	const FIELD_DESCRIPTION_1 = 'Description for the first similar field';
	const FIELD_TYPE_NAME_1 = 'Int';
	const FIELD_TYPE_NAME_2 = 'Date';
	const VALID_DESCRIPTION = 'Description of the interface';


	public function test_GivenInterfaceDeclaration_getName_WillReturnName() {
		$interface = $this->GivenInterfaceDeclaration();

		$retVal = $interface->getName();

		$this->assertEquals(self::VALID_NAME, $retVal);
	}

	public function test_GivenInterfaceDeclaration_getConstantsDeclaration_WillReturnNull() {
		$interface = $this->GivenInterfaceDeclaration();

		$retVal = $interface->getConstantsDeclaration();

		$this->assertNull($retVal);
	}

	public function test_GivenInterfaceDeclarationWithNoFields_getDependencies_WillBeEmpty() {
		$interface = $this->GivenInterfaceDeclarationWithNoFields();

		$retVal = $interface->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenInterfaceDeclarationWith2DistinctFields_getDependencies_WillReturn2Values() {
		$interface = $this->GivenInterfaceDeclarationWith2DistinctFields();

		$retVal = $interface->getDependencies();

		$this->assertCount(2, $retVal);
	}

	public function test_GivenInterfaceDeclarationWith2DistinctFields_getDependencies_WillReturn2DistinctValues() {
		$interface = $this->GivenInterfaceDeclarationWith2DistinctFields();

		$retVal = $interface->getDependencies();

		$this->assertCount(2, $retVal);
	}

	public function test_GivenInterfaceDeclaration_generateTypeDefinition_WillContainName() {
		$interface = $this->GivenInterfaceDeclaration();

		$retVal = $interface->generateTypeDefinition();

		$this->assertContains(self::VALID_NAME, $retVal);
	}

	public function test_GivenInterfaceDeclarationWithDescription_generateTypeDefinition_WillContainDescription() {
		$interface = $this->GivenInterfaceDeclarationWithDescription();

		$retVal = $interface->generateTypeDefinition();

		$this->assertContains(self::VALID_DESCRIPTION, $retVal);
	}

	protected function GivenInterfaceDeclaration() {
		return new InterfaceDeclaration(
			self::VALID_NAME,
			null,
			new StubFormatter(),
			null
		);
	}

	protected function GivenInterfaceDeclarationWithDescription() {
		return new InterfaceDeclaration(
			self::VALID_NAME,
			null,
			new StubFormatter(),
			self::VALID_DESCRIPTION
		);
	}

	protected function GivenInterfaceDeclarationWithNoFields() {
		return new InterfaceDeclaration(
			self::VALID_NAME,
			[],
			new StubFormatter(),
			null
		);
	}

	protected function GivenInterfaceDeclarationWith2DistinctFields() {
		$field1 = new Field(
			self::FIELD_NAME_1,
			self::FIELD_DESCRIPTION_1,
			new TypeUsage(
				self::FIELD_TYPE_NAME_1,
				false,
				false,
				false
			),
			[]
		);

		$field2 = new Field(
			self::FIELD_NAME_2,
			null,
			new TypeUsage(
				self::FIELD_TYPE_NAME_2,
				false,
				false,
				false
			),
			[]
		);

		return new InterfaceDeclaration(
			self::VALID_NAME,
			[$field1, $field2],
			new StubFormatter(),
			null
		);
	}
}