<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;

class ResolverContentTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnum';
	const INTERFACE_NAME = 'AnInterface';
	const TYPE_NAME = 'AType';
	const FIELD_NAME = 'AFieldName';
	const FIELD_NAME_TYPE = 'AFieldNameType';
	/**
	 * @var ResolverContent
	 */
	protected $_resolverContent;

	public function setUp() {
		$this->_resolverContent = new ResolverContent();
	}

	public function test_GivenScalarGeneratorType_getContent_WontContainResolve() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenTypeNoFieldsGeneratorType_getContent_WontContainResolve() {
		$this->GivenTypeNoFieldsGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenTypeWithFieldsGeneratorType_getContent_WillContainResolve() {
		$this->GivenTypeWithFieldsGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertContains('resolve', $retVal);
	}

	public function test_GivenNothing_getVariables_WillAlwaysBeEmpty() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getVariables();

		$this->assertEmpty($retVal);
	}

	public function test_GivenScalarGeneratorType_getTypeGeneratorClass_WillReturnRightType() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getTypeGeneratorClass();

		$this->assertEquals(Scalar::class, $retVal);
	}

	protected function GivenScalarGeneratorType() {
		$this->_resolverContent->setTypeGenerator(new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		));
	}

	protected function GivenTypeNoFieldsGeneratorType() {
		$this->_resolverContent->setTypeGenerator(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[]
		));
	}

	protected function GivenTypeWithFieldsGeneratorType() {
		$this->_resolverContent->setTypeGenerator(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[ new Field(self::FIELD_NAME, null, new TypeUsage(self::FIELD_NAME_TYPE, false, false, false), [])]
		));
	}
}