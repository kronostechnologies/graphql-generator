<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;

class ObjectTypeContentTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnum';
	const INTERFACE_NAME = 'AnInterface';
	/**
	 * @var ObjectTypeContent
	 */
	protected $_objectTypeContent;

	public function setUp() {
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

	protected function GivenScalarGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		));
	}

	protected function GivenEnumGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new Enum(
			self::ENUM_NAME,
			[],
			new StubFormatter()
		));
	}

	protected function GivenInterfaceGeneratorType() {
		$this->_objectTypeContent->setGeneratorType(new InterfaceDeclaration(
			self::INTERFACE_NAME,
			[],
			new StubFormatter()
		));
	}
}