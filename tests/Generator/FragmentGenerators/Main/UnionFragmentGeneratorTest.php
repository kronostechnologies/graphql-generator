<?php


namespace GraphQLGen\Tests\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class UnionFragmentGeneratorTest extends \PHPUnit_Framework_TestCase {
	const UNION_DESCRIPTION = 'A short description';
	const UNION_NAME = 'UnionName';
	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_stubFormatter = $this->getMockBuilder(StubFormatter::class)
			->setMethods(['getFieldTypeDeclaration', 'getFieldTypeDeclarationNonPrimaryType', 'getResolveFragment', 'getResolveFragmentForUnion'])
			->getMock();
	}

	public function test_GivenUnionTypeWithoutDescription_generateTypeDefinition_WillContainName() {
		$union = $this->GivenUnionTypeWithoutDescription();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains(self::UNION_NAME, $retVal);
	}

	public function test_GivenUnionTypeWithoutDescription_generateTypeDefinition_WontContainDescriptionFragment() {
		$union = $this->GivenUnionTypeWithoutDescription();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotContains("'description'", $retVal);
	}

	public function test_GivenUnionTypeWithDescription_generateTypeDefinition_WillContainDescriptionFragment() {
		$union = $this->GivenUnionTypeWithDescription();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'description'", $retVal);
	}

	public function test_GivenUnionTypeWithoutDescription_generateTypeDefinition_WillContainTypes() {
		$union = $this->GivenUnionTypeWithoutDescription();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'types'", $retVal);
	}

	public function test_GivenUnionTypeWithoutDescription_generateTypeDefinition_WillContainResolveTypeFragment() {
		$union = $this->GivenUnionTypeWithoutDescription();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertContains("'resolveType'", $retVal);
	}

	public function test_GivenUnionTypeWithFields_generateTypeDefinition_WillContainResolveTypeFragment() {
		$union = $this->GivenUnionTypeWithFields();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->generateTypeDefinition();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenUnionTypeWithFields_getDependencies_WontBeEmpty() {
		$union = $this->GivenUnionTypeWithFields();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->getDependencies();

		$this->assertNotEmpty($retVal);
	}

	public function test_GivenUnionTypeWithoutFields_getDependencies_WillBeEmpty() {
		$union = $this->GivenUnionTypeWithoutFields();
		$generator = $this->GivenUnionTypeGenerator($union);

		$retVal = $generator->getDependencies();

		$this->assertEmpty($retVal);
	}

	protected function GivenUnionTypeWithoutDescription() {
		$union = new UnionInterpretedType();
		$union->setName(self::UNION_NAME);

		return $union;
	}

	protected function GivenUnionTypeWithoutFields() {
		$union = new UnionInterpretedType();
		$union->setName(self::UNION_NAME);

		return $union;
	}

	protected function GivenUnionTypeWithDescription() {
		$union = new UnionInterpretedType();
		$union->setName(self::UNION_NAME);
		$union->setDescription(self::UNION_DESCRIPTION);

		return $union;
	}

	protected function GivenUnionTypeWithFields() {
		$type1 = new TypeUsageInterpretedType();
		$type2 = new TypeUsageInterpretedType();

		$union = new UnionInterpretedType();
		$union->setName(self::UNION_NAME);
		$union->setDescription(self::UNION_DESCRIPTION);
		$union->setTypes([$type1, $type2]);

		return $union;
	}

	protected function GivenUnionTypeGenerator($union) {
		$generator = new UnionFragmentGenerator();
		$generator->setUnionType($union);
		$generator->setFormatter($this->_stubFormatter);

		return $generator;
	}
}