<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
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

	/**
	 * @var StubFormatter|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFormatter;

	public function setUp() {
		$this->_objectTypeClass = $this->createMock(ObjectType::class);

		$this->_stubFormatter = $this->createMock(StubFormatter::class);

		$this->_objectTypeContent = new ObjectTypeContent();
	}

	public function test_GivenScalarGeneratorType_getContent_WillContainConstructor() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains('function __construct', $retVal);
	}

	public function test_GivenScalarGeneratorType_getContent_WillContainConstructorWithoutResolverFactory() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertNotContains('__construct($resolverFactory', $retVal);
	}

	public function test_GivenInterfaceGeneratorType_getContent_WillContainConstructorWithResolverFactory() {
		$this->GivenInterfaceGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains('__construct($resolverFactory', $retVal);
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

	public function test_GivenInterfaceGeneratorType_getContent_WillContainInstanciation() {
		$this->GivenInterfaceGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains("\$this->resolver = \$resolverFactory->", $retVal);
	}

	public function test_GivenScalarGeneratorType_getContent_WontContainResolverInstanciation() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_objectTypeContent->getContent();

		$this->assertContains("\$this->resolver = \$resolverFactory->", $retVal);
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
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);
		$scalarTypeFragment->setFormatter($this->_stubFormatter);

		$this->_objectTypeContent->setFragmentGenerator($scalarTypeFragment);
	}

	protected function GivenEnumGeneratorType() {
		$enumType = new EnumInterpretedType();
		$enumType->setName(self::ENUM_NAME);

		$enumTypeFragment = new EnumFragmentGenerator();
		$enumTypeFragment->setEnumType($enumType);
		$enumTypeFragment->setFormatter($this->_stubFormatter);

		$this->_objectTypeContent->setFragmentGenerator($enumTypeFragment);
	}

	protected function GivenInterfaceGeneratorType() {
		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::INTERFACE_NAME);

		$interfaceTypeFragment = new InterfaceFragmentGenerator();
		$interfaceTypeFragment->setInterfaceType($interfaceType);
		$interfaceTypeFragment->setFormatter($this->_stubFormatter);

		$this->_objectTypeContent->setFragmentGenerator($interfaceTypeFragment);
	}

	protected function GivenObjectTypeClass() {
		return new ObjectType();
	}

	protected function GivenObjectTypeClassMock() {
		$this->_objectTypeContent->setObjectTypeClass($this->_objectTypeClass);
	}
}