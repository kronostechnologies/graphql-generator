<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Tests\Old\Mocks\InvalidGeneratorType;

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
		$enumType = new EnumInterpretedType();
		$enumType->setName(self::ENUM_NAME);

		$enumTypeFragment = new EnumFragmentGenerator();
		$enumTypeFragment->setEnumType($enumType);

		$this->_objectType->setFragmentGenerator($enumTypeFragment);
	}

	protected function GivenTypeGeneratorType() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_objectType->setFragmentGenerator($objectTypeFragment);
	}

	protected function GivenScalarGeneratorType() {
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);

		$this->_objectType->setFragmentGenerator($scalarTypeFragment);
	}

	protected function GivenInterfaceGeneratorType() {
		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::INTERFACE_NAME);

		$interfaceTypeFragment = new InterfaceFragmentGenerator();
		$interfaceTypeFragment->setInterfaceType($interfaceType);

		$this->_objectType->setFragmentGenerator($interfaceTypeFragment);
	}

	protected function GivenInvalidGeneratorType() {
		$this->_objectType->setFragmentGenerator(new InvalidGeneratorType());
	}
}