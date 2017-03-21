<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Tests\Mocks\InvalidGeneratorType;

class ObjectTypeTest extends \PHPUnit_Framework_TestCase {
	const ENUM_NAME = 'AnEnum';
	const TYPE_NAME = 'AType';
	const SCALAR_NAME = 'ScalarName';
	const INTERFACE_NAME = 'InterfaceName';
	/**
	 * @var ObjectType
	 */
	protected $_objectType;

	public function setUp() {
		$this->_objectType = new ObjectType();
	}

	public function test_GivenEnumGeneratorType_getStubFileName_WillReturnEnumStub() {
		$this->GivenEnumGeneratorType();

		$retVal = $this->_objectType->getStubFileName();

		$this->assertEquals(ObjectType::ENUM_STUB, $retVal);
	}

	public function test_GivenTypeGeneratorType_getStubFileName_WillReturnEnumStub() {
		$this->GivenTypeGeneratorType();

		$retVal = $this->_objectType->getStubFileName();

		$this->assertEquals(ObjectType::OBJECT_STUB, $retVal);
	}

	public function test_GivenScalarGeneratorType_getStubFileName_WillReturnEnumStub() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectType->getStubFileName();

		$this->assertEquals(ObjectType::SCALAR_STUB, $retVal);
	}

	public function test_GivenInterfaceGeneratorType_getStubFileName_WillReturnEnumStub() {
		$this->GivenInterfaceGeneratorType();

		$retVal = $this->_objectType->getStubFileName();

		$this->assertEquals(ObjectType::INTERFACE_STUB, $retVal);
	}

	public function test_GivenEnumGeneratorType_getContentCreator_ReturnsContentCreator() {
		$this->GivenEnumGeneratorType();

		$retVal = $this->_objectType->getContentCreator();

		$this->assertInstanceOf(BaseContentCreator::class, $retVal);
	}

	public function test_GivenInvalidGeneratorType_getStubFileName_WillThrowException() {
		$this->GivenInvalidGeneratorType();

		$this->expectException(Exception::class);

		$this->_objectType->getStubFileName();
	}

	protected function GivenEnumGeneratorType() {
		$this->_objectType->setGeneratorType(new Enum(
			self::ENUM_NAME, new StubFormatter(), []
		));
	}

	protected function GivenTypeGeneratorType() {
		$this->_objectType->setGeneratorType(new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[],
            []
		));
	}

	protected function GivenScalarGeneratorType() {
		$this->_objectType->setGeneratorType(new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		));
	}

	protected function GivenInterfaceGeneratorType() {
		$this->_objectType->setGeneratorType(new InterfaceDeclaration(
			self::INTERFACE_NAME, new StubFormatter(), []
		));
	}

	protected function GivenInvalidGeneratorType() {
		$this->_objectType->setGeneratorType(new InvalidGeneratorType());
	}
}