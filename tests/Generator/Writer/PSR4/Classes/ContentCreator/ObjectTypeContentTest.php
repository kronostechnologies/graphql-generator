<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;

class ObjectTypeContentTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnum';
	const INTERFACE_NAME = 'AnInterface';
	/**
	 * @var ObjectTypeContent
	 */
	protected $_objectTypeContent;

	/**
	 * @var ObjectType|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_objectTypeClass;

	public function setUp() {
		$this->_objectTypeClass = $this->createMock(ObjectType::class);

		$this->_objectTypeContent = new ObjectTypeContent();
	}

	public function test_GivenScalarGeneratorType_getContent_WillContainConstructor() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains('function __construct', $retVal);
	}

	public function test_GivenScalarGeneratorType_getContent_WillContainParentConstructor() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains('parent::__construct(', $retVal);
	}

	public function test_GivenScalarGeneratorType_getContent_WillContainParentSimpleConstructor() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains('parent::__construct();', $retVal);
	}

	public function test_GivenInterfaceGeneratorType_getContent_WillContainResolverNew() {
		$this->GivenInterfaceGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains("\$this->resolver = new", $retVal);
	}

	public function test_GivenScalarGeneratorType_getContent_WontContainResolverNew() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertNotContains("\$this->resolver = new", $retVal);
	}

	public function test_GivenEnumGeneratorType_getContent_WillContainParentComplexConstructor() {
		$this->GivenEnumGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertNotContains('parent::__construct();', $retVal);
	}

	public function test_GivenScalarGeneratorType_getVariables_WontContainResolver() {
		$this->GivenEnumGeneratorType();

		$retVal = $this->_objectTypeContent->getVariables();

		$this->assertNotContains('\$resolver', $retVal);
	}

	public function test_GivenInterfaceGeneratorType_getVariables_WontContainResolver() {
		$this->GivenInterfaceGeneratorType();

		$retVal = $this->_objectTypeContent->getVariables();

		$this->assertContains('$resolver', $retVal);
	}

	public function test_GivenSetObjectTypeClass_getObjectTypeClass_WillReturnObjectTypeClass() {
		$objTypeClass = $this->GivenObjectTypeClass();

		$this->_objectTypeContent->setObjectTypeClass($objTypeClass);
		$retVal = $this->_objectTypeContent->getObjectTypeClass();

		$this->assertEquals($objTypeClass, $retVal);
	}

	public function test_GivenObjectTypeClassMock_getNamespace_WillFetchObjectTypeNS() {
		$this->GivenObjectTypeClassMock();

		$this->_objectTypeClass->expects($this->once())->method('getNamespace');

		$this->_objectTypeContent->getNamespace();
	}

	public function test_GivenObjectTypeClassMock_getClassName_WillFetchObjectTypeClassName() {
		$this->GivenObjectTypeClassMock();

		$this->_objectTypeClass->expects($this->once())->method('getClassName');

		$this->_objectTypeContent->getClassName();
	}

	public function test_GivenObjectTypeClassMock_getParentClassName_WillFetchObjectTypeClassName() {
		$this->GivenObjectTypeClassMock();

		$this->_objectTypeClass->expects($this->once())->method('getParentClassName');

		$this->_objectTypeContent->getParentClassName();
	}

	protected function GivenScalarGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		));
	}

	protected function GivenEnumGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new Enum(
			self::ENUM_NAME, new StubFormatter(), []
		));
	}

	protected function GivenInterfaceGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new InterfaceDeclaration(
			self::INTERFACE_NAME, new StubFormatter(), []
		));
	}

	protected function GivenObjectTypeClass() {
		return new ObjectType();
	}

	protected function GivenObjectTypeClassMock() {
		$this->_objectTypeContent->setObjectTypeClass($this->_objectTypeClass);
	}
}