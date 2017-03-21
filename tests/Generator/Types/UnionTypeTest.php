<?php


namespace GraphQLGen\Tests\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Union;

class UnionTypeTest extends \PHPUnit_Framework_TestCase {
	const TYPE_USAGE_1_NAME = 'FirstTypeName';
	const TYPE_USAGE_2_NAME = 'SecondTypeName';
	const UNION_DESC = 'A Description of a union';
	const UNION_NAME = 'AUnion';
	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatterMock;

	public function setUp() {
		$this->_stubFormatterMock = $this->createMock(StubFormatter::class);
	}

	public function test_GivenTypeWithoutDescription_generateTypeDefinition_WillContainNameFragment() {
		$type = $this->GivenTypeWithoutDescription();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'name'", $retVal);
	}

	public function test_GivenTypeWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$type = $this->GivenTypeWithoutDescription();

		$retVal = $type->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenTypeWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$type = $this->GivenTypeWithDescription();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenTypeWithoutDescription_generateTypeDefinition_WillContainTypesFragment() {
		$type = $this->GivenTypeWithoutDescription();

		$retVal = $type->generateTypeDefinition();

		$this->assertContains("'types'", $retVal);
	}

	public function test_GivenTypeWithoutTypes_getDependencies_WillBeEmpty() {
		$type = $this->GivenTypeWithoutTypes();

		$retVal = $type->getDependencies();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeWithTypes_getDependencies_WontBeEmpty() {
		$type = $this->GivenTypeWithTypes();

		$retVal = $type->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	protected function GivenTypeWithoutDescription() {
		return new Union(
			self::UNION_NAME,
			$this->_stubFormatterMock,
			[]
		);
	}

	protected function GivenTypeWithDescription() {
		return new Union(
			self::UNION_NAME,
			$this->_stubFormatterMock,
			[],
			self::UNION_DESC
		);
	}

	protected function GivenTypeWithoutTypes() {
		return new Union(
			self::UNION_NAME,
			$this->_stubFormatterMock,
			[]
		);
	}

	protected function GivenTypeWithTypes() {
		$type1 = new TypeUsage(
			self::TYPE_USAGE_1_NAME,
			false,
			false,
			false
		);

		$type2 = new TypeUsage(
			self::TYPE_USAGE_2_NAME,
			false,
			false,
			false
		);

		return new Union(
			self::UNION_NAME,
			$this->_stubFormatterMock,
			[$type1, $type2]
		);
	}
}