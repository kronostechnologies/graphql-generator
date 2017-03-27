<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;
use GraphQLGen\Generator\Writer\PSR4\ClassMapper;
use GraphQLGen\Tests\Mocks\InvalidGeneratorType;

class ClassMapperTest extends \PHPUnit_Framework_TestCase {
	const BASE_NS_VALID = 'Acme\\Test';
	const TYPE_NAME = 'TypeName';
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnumeration';
	const INTERFACE_NAME = 'InterfaceType';
	const OBJ_TYPE_CLASS_NAME = 'AnObjType';

	/**
	 * @var ClassMapper
	 */
	protected $_classMapper;

	/**
	 * @var TypeStore|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_typeStore;

	public function setUp() {
		$this->_typeStore = $this->createMock(TypeStore::class);

		$this->_classMapper = new ClassMapper();
		$this->_classMapper->setTypeStore($this->_typeStore);
	}

	public function test_GivenTypeAndBaseNamespace_getNamespaceForGenerator_WillStartWithBaseNamespace() {
		$this->_classMapper->setBaseNamespace(self::BASE_NS_VALID);
		$givenType = $this->GivenType();

		$retVal = $this->_classMapper->getNamespaceForFragmentGenerator($givenType);

		$this->assertStringStartsWith(self::BASE_NS_VALID, $retVal);
	}

	public function test_GivenType_getNamespaceForGenerator_WillEndWithTypes() {
		$givenType = $this->GivenType();

		$retVal = $this->_classMapper->getNamespaceForFragmentGenerator($givenType);

		$this->assertStringEndsWith("Types", $retVal);
	}

	public function test_GivenScalar_getNamespaceForGenerator_WillEndWithScalars() {
		$givenType = $this->GivenScalar();

		$retVal = $this->_classMapper->getNamespaceForFragmentGenerator($givenType);

		$this->assertStringEndsWith("Scalars", $retVal);
	}

	public function test_GivenEnum_getNamespaceForGenerator_WillEndWithEnums() {
		$givenType = $this->GivenEnum();

		$retVal = $this->_classMapper->getNamespaceForFragmentGenerator($givenType);

		$this->assertStringEndsWith("Enums", $retVal);
	}

	public function test_GivenInterface_getNamespaceForGenerator_WillEndWithInterfaces() {
		$givenType = $this->GivenInterface();

		$retVal = $this->_classMapper->getNamespaceForFragmentGenerator($givenType);

		$this->assertStringEndsWith("Interfaces", $retVal);
	}

	public function test_GivenInvalidGeneratorType_getNamespaceForGenerator_WillThrowException() {
		$givenType = $this->GivenInvalidGeneratorType();

		$this->expectException(Exception::class);

		$this->_classMapper->getNamespaceForFragmentGenerator($givenType);
	}

	public function test_GivenType_getResolverNamespaceFromGenerator_WillEndWithResolversType() {
		$givenType = $this->GivenType();

		$retVal = $this->_classMapper->getResolverNamespaceFromGenerator($givenType);

		$this->assertStringEndsWith("Resolvers\\Types", $retVal);
	}

	public function test_GivenInterface_getResolverNamespaceFromGenerator_WillEndWithResolversTypeEnum() {
		$givenType = $this->GivenInterface();

		$retVal = $this->_classMapper->getResolverNamespaceFromGenerator($givenType);

		$this->assertStringEndsWith("Resolvers\\Types\\Interfaces", $retVal);
	}

	public function test_GivenInvalidGeneratorType_getResolverNamespaceFromGenerator_WillThrowException() {
		$givenType = $this->GivenInvalidGeneratorType();

		$this->expectException(Exception::class);

		$this->_classMapper->getResolverNamespaceFromGenerator($givenType);
	}

	public function test_GivenType_getParentDependencyForGenerator_WillReturnCorrectDependency() {
		$givenType = $this->GivenType();

		$retVal = $this->_classMapper->getParentDependencyForFragmentGenerator($givenType);

		$this->assertEquals("ObjectType", $retVal);
	}

	public function test_GivenScalar_getParentDependencyForGenerator_WillReturnCorrectDependency() {
		$givenType = $this->GivenScalar();

		$retVal = $this->_classMapper->getParentDependencyForFragmentGenerator($givenType);

		$this->assertEquals("ScalarType", $retVal);
	}

	public function test_GivenEnum_getParentDependencyForGenerator_WillReturnCorrectDependency() {
		$givenType = $this->GivenEnum();

		$retVal = $this->_classMapper->getParentDependencyForFragmentGenerator($givenType);

		$this->assertEquals("EnumType", $retVal);
	}

	public function test_GivenInterface_getParentDependencyForGenerator_WillReturnCorrectDependency() {
		$givenType = $this->GivenInterface();

		$retVal = $this->_classMapper->getParentDependencyForFragmentGenerator($givenType);

		$this->assertEquals("InterfaceType", $retVal);
	}

	public function test_GivenInvalidGeneratorType_getParentDependencyForGenerator_WillThrowException() {
		$givenType = $this->GivenInvalidGeneratorType();

		$this->expectException(Exception::class);

		$this->_classMapper->getParentDependencyForFragmentGenerator($givenType);
	}

	public function test_GivenTwiceSameClass_addClass_WillCollide() {
		$givenClass = $this->GivenObjectClass();

		$this->_classMapper->registerClass($givenClass);

		$this->expectException(Exception::class);

		$this->_classMapper->registerClass($givenClass);
	}

	public function test_GivenAddedClass_getClasses_WillContainClass() {
		$givenClass = $this->GivenObjectClass();

		$this->_classMapper->registerClass($givenClass);
		$retVal = $this->_classMapper->getClasses();

		$this->assertContains($givenClass, $retVal);
	}

	public function test_GivenDependencyNotResolved_getResolvedDependency_WillThrowException() {
		$this->expectException(Exception::class);

		$this->_classMapper->getResolvedDependency('Error');
	}

	public function test_GivenDependencyResolved_getResolvedDependency_WillReturnRightNS() {
		$this->_classMapper->registerDependency("LocalTest", "A\\Namespace");
		$retVal = $this->_classMapper->getResolvedDependency("LocalTest");

		$this->assertEquals("A\\Namespace", $retVal);
	}

	public function test_GivenAsTypeImplementation_mapClass_WillAddToTypeStore() {
		$this->_typeStore->expects($this->once())->method('addTypeImplementation');

		$this->_classMapper->registerTypeStoreEntry("", $this->GivenObjectClass());
	}

	public function test_GivenNothing_setInitialMappings_WillSucceed() {
		try {
			$this->_classMapper->setInitialMappings();
		} catch (Exception $ex) {
			$this->fail($ex->getMessage());
		}
	}

	public function test_GivenSetNamespace_getBaseNamespace_WillReturnNamespace() {
		$this->_classMapper->setBaseNamespace(self::BASE_NS_VALID);

		$retVal = $this->_classMapper->getBaseNamespace();

		$this->assertEquals(self::BASE_NS_VALID, $retVal);
	}

	protected function GivenType() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		return $objectTypeFragment;
	}

	protected function GivenScalar() {
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);

		return $scalarTypeFragment;
	}

	protected function GivenEnum() {
		$enumType = new EnumInterpretedType();
		$enumType->setName(self::ENUM_NAME);

		$enumTypeFragment = new EnumFragmentGenerator();
		$enumTypeFragment->setEnumType($enumType);

		return $enumTypeFragment;
	}

	protected function GivenInterface() {
		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::INTERFACE_NAME);

		$interfaceTypeGenerator = new InterfaceFragmentGenerator();
		$interfaceTypeGenerator->setInterfaceType($interfaceType);

		return $interfaceTypeGenerator;
	}

	protected function GivenInvalidGeneratorType() {
		return new InvalidGeneratorType();
	}

	protected function GivenObjectClass() {
		$objectTypeClass = new ObjectType();
		$objectTypeClass->setClassName(self::OBJ_TYPE_CLASS_NAME);

		return $objectTypeClass;
	}
}