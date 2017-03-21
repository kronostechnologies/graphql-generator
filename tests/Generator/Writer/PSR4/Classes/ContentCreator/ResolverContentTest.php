<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use Exception;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContentTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnum';
	const INTERFACE_NAME = 'AnInterface';
	const TYPE_NAME = 'AType';
	const FIELD_NAME = 'AFieldName';
	const FIELD_NAME_TYPE = 'AFieldNameType';
	const FIELD_PRIMARY_TYPE = 'ID';
	const FIELD_NON_PRIMARY_TYPE = 'ANonPrimaryType';
	const CLASS_NAME = 'Class123';
	const CLASS_NS = 'A\\Namespace';
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

	public function test_GivenPrimaryType_getContent_WontContainResolve() {
		$this->GivenPrimaryType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenNonPrimaryType_getContent_WillContainResolve() {
		$this->GivenNonPrimaryType();

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

	public function test_GivenSetResolverClass_getResolverClass_WillReturnResolverClass() {
		$resolverClass = $this->GivenResolverClass();
		$this->_resolverContent->setResolverClass($resolverClass);

		$retVal = $this->_resolverContent->getResolverClass();

		$this->assertEquals($resolverClass, $retVal);
	}

	public function test_GivenNothing_getParentClassName_WillBeEmpty() {
		$retVal = $this->_resolverContent->getParentClassName();

		$this->assertEmpty($retVal);
	}

	public function test_GivenResolverClass_getTypeGeneratorClass_WillCrash() {
		$resolverClass = $this->GivenResolverClass();
		$this->_resolverContent->setResolverClass($resolverClass);

		$this->expectException(Exception::class);

		$this->_resolverContent->getTypeGeneratorClass();
	}

	public function test_GivenResolverClassMock_getClassName_WillProxyThroughResolverClass() {
		$resolverClass = $this->GivenResolverClassMock();
		$this->_resolverContent->setResolverClass($resolverClass);

		$resolverClass->expects($this->once())->method('getClassName');

		$this->_resolverContent->getClassName();
	}

	public function test_GivenResolverClassMock_getNamespace_WillProxyThroughResolverClass() {
		$resolverClass = $this->GivenResolverClassMock();
		$this->_resolverContent->setResolverClass($resolverClass);

		$resolverClass->expects($this->once())->method('getNamespace');

		$this->_resolverContent->getNamespace();
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
			[],
            []
		));
	}

	protected function GivenTypeWithFieldsGeneratorType() {
		$this->_resolverContent->setTypeGenerator(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[ new Field(self::FIELD_NAME, null, new TypeUsage(self::FIELD_NAME_TYPE, false, false, false), [])],
            []
		));
	}

	private function GivenResolverClass() {
		return new Resolver();
	}

	private function GivenResolverClassMock() {
		return $this->createMock(Resolver::class);
	}

	private function GivenPrimaryType() {
		$this->_resolverContent->setTypeGenerator(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[ new Field(self::FIELD_NAME, null, new TypeUsage(self::FIELD_PRIMARY_TYPE, false, false, false), [])],
			[]
		));
	}

	private function GivenNonPrimaryType() {
		$this->_resolverContent->setTypeGenerator(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[ new Field(self::FIELD_NAME, null, new TypeUsage(self::FIELD_NON_PRIMARY_TYPE, false, false, false), [])],
			[]
		));
	}
}