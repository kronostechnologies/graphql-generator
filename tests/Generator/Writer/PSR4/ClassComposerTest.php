<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\DTO;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;
use GraphQLGen\Generator\Writer\PSR4\ClassesFactory;
use GraphQLGen\Generator\Writer\PSR4\ClassMapper;
use PHPUnit_Framework_MockObject_MockObject;

class ClassComposerTest extends \PHPUnit_Framework_TestCase {
	const OBJ_TYPE_NAME = 'AnObjectType';
	const OBJ_TYPE_DESC = 'ADescription';
	const IFACE_TYPE_NAME = 'AnInterface';
	const IFACE_TYPE_DESC = 'ADescriptionOfInterface';
	const ENUM_NAME = 'AnEnumName';
	const ENUM_DESC = 'DescriptionOfAnEnum';
	const PARENT_CLASS_NAME = 'ParentClassName';
	/**
	 * @var ClassesFactory|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_factory;
	/**
	 * @var ClassComposer
	 */
	protected $_classesComposer;

	/**
	 * @var ClassMapper|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classMapper;

	/**
	 * @var TypeStore|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_typeStore;

	/**
	 * @var TypeStore|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_typeStoreMock;
	/**
	 * @var Resolver|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_resolverMock;
	/**
	 * @var ObjectType|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_objTypeMock;

	/**
	 * @var DTO|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_dtoTypeMock;

	public function setUp() {
		$this->_dtoTypeMock = $this->createMock(DTO::class);
		$this->_objTypeMock = $this->createMock(ObjectType::class);
		$this->_objTypeMock->method('getParentClassName')->willReturn(self::PARENT_CLASS_NAME);
		$this->_resolverMock = $this->createMock(Resolver::class);
		$this->_typeStoreMock = $this->createMock(TypeStore::class);

		$this->_factory = $this->createMock(ClassesFactory::class);
		$this->_factory->method('createDTOClass')->willReturn($this->_dtoTypeMock);
		$this->_factory->method('createObjectTypeClass')->willReturn($this->_objTypeMock);
		$this->_factory->method('createResolverClass')->willReturn($this->_resolverMock);
		$this->_factory->method('createTypeStoreClass')->willReturn($this->_typeStoreMock);

		$this->_typeStore = $this->createMock(TypeStore::class);

		$this->_classMapper = $this->createMock(ClassMapper::class);
		$this->_classMapper->method('getTypeStore')->willReturn($this->_typeStore);

		$this->_classesComposer = new ClassComposer($this->_factory);
		$this->_classesComposer->setClassMapper($this->_classMapper);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillCreateObjectTypeClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createObjectTypeClass');

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillFetchNamespaceWithType() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper->expects($this->once())->method('getNamespaceForGenerator')->with($givenType);

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillFetchParentDependenciesWithType() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper->expects($this->once())->method('getParentDependencyForGenerator')->with($givenType);

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillAddCorrectDependencies() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_objTypeMock
			->expects($this->exactly(4))
			->method('addDependency')
			->with($this->logicalOr(
				$givenType->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX,
				ClassComposer::TYPE_STORE_CLASS_NAME,
				ClassComposer::TYPE_CLASS_NAME,
				$this->_objTypeMock->getParentClassName()
			));

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectTypeWithGeneratorClassReturned_generateClassForGenerator_WillAddTypeNameDependency() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_typeStore
			->expects($this->any())
			->method('addDependency')
			->with($givenType->getName());

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectTypeWithGeneratorClassReturned_generateClassForGenerator_ClassMapperWillMapClassNameToValueCorrectly() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapClass')
			->with($givenType->getName(), $this->_objTypeMock, true);

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenInterfaceObjectType_generateClassForGenerator_WillAddCorrectDependencies() {
		$givenType = $this->GivenInterfaceObjectType();

		$this->_objTypeMock
			->expects($this->exactly(4))
			->method('addDependency')
			->with($this->logicalOr(
				$givenType->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX,
				ClassComposer::TYPE_STORE_CLASS_NAME,
				ClassComposer::TYPE_CLASS_NAME,
				$this->_objTypeMock->getParentClassName()
			));

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenEnumObjectType_generateClassForGenerator_WontAddTypeStoreDependencyWithTypeName() {
		$givenType = $this->GivenEnumObjectType();

		$this->_objTypeMock
			->expects($this->exactly(3))
			->method('addDependency')
			->with($this->logicalOr(
				ClassComposer::TYPE_STORE_CLASS_NAME,
				ClassComposer::TYPE_CLASS_NAME,
				$this->_objTypeMock->getParentClassName()
			));

		$this->_classesComposer->generateClassForGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_WillGenerateResolverClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createResolverClass')
			->with($givenType);

		$this->_classesComposer->generateResolverForGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_WillResolveNamespaceForClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('getResolverNamespaceFromGenerator')
			->with($givenType);

		$this->_classesComposer->generateResolverForGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_ClassMapperWillMapClassNameToValueCorrectly() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapClass')
			->with($this->_resolverMock->getClassName(), $this->_resolverMock, false);

		$this->_classesComposer->generateResolverForGenerator($givenType);
	}

	public function test_GivenAnything_generateUniqueTypeStore_WillGenerateTypeStoreClass() {
		$this->_factory
			->expects($this->once())
			->method('createTypeStoreClass');

		$this->_classesComposer->generateUniqueTypeStore();
	}

	public function test_GivenAnything_generateUniqueTypeStore_WillFetchBaseClassMapperNamespace() {
		$this->_classMapper
			->expects($this->once())
			->method('getBaseNamespace');

		$this->_classesComposer->generateUniqueTypeStore();
	}

	public function test_GivenAnything_generateUniqueTypeStore_WillSetClassMapperTypeStore() {
		$this->_classMapper
			->expects($this->once())
			->method('setTypeStore')
			->with($this->_typeStoreMock);

		$this->_classesComposer->generateUniqueTypeStore();
	}

	public function test_GivenAnything_generateUniqueTypeStore_ClassMapperWillMapClassNameToValueCorrectly() {
		$this->_classMapper
			->expects($this->once())
			->method('mapClass')
			->with($this->_typeStoreMock->getClassName(), $this->_typeStoreMock, false);

		$this->_classesComposer->generateUniqueTypeStore();
	}

	public function test_GivenAnything_generateDTOForGenerator_WillCreateDTOClass() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createDTOClass');

		$this->_classesComposer->generateDTOForGenerator($givenObjectType);
	}

	public function test_GivenAnything_generateDTOForGenerator_WillSetNamespace() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_dtoTypeMock
			->expects($this->once())
			->method('setNamespace');

		$this->_classesComposer->generateDTOForGenerator($givenObjectType);
	}

	public function test_GivenObjectTypeWithDependencies_generateDTOForGenerator_WillAddDependencies() {
		$givenObjectType = $this->GivenObjectTypeWithDependencies();

		$this->_dtoTypeMock
			->expects($this->any())
			->method('addDependency');

		$this->_classesComposer->generateDTOForGenerator($givenObjectType);
	}

	public function test_GivenAnything_generateDTOForGenerator_WillMapClass() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapClass')
			->with($this->_dtoTypeMock->getClassName(), $this->_dtoTypeMock, false);;

		$this->_classesComposer->generateDTOForGenerator($givenObjectType);
	}

	protected function GivenObjectTypeFragmentGenerator() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::OBJ_TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		return $objectTypeFragment;
	}

	protected function GivenObjectTypeWithDependencies() {
		$fieldType1 = new TypeUsageInterpretedType();
		$fieldType1->setTypeName("ADep");

		$objectTypeField1 = new FieldInterpretedType();
		$objectTypeField1->setFieldType($fieldType1);

		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::OBJ_TYPE_NAME);
		$objectType->setFields([$objectTypeField1]);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		return $objectTypeFragment;
	}

	protected function GivenInterfaceObjectType() {
		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::IFACE_TYPE_NAME);
		$interfaceType->setDescription(self::IFACE_TYPE_DESC);

		$interfaceTypeFragment = new InterfaceFragmentGenerator();
		$interfaceTypeFragment->setInterfaceType($interfaceType);

		return $interfaceTypeFragment;
	}

	protected function GivenEnumObjectType() {
		$enumType = new EnumInterpretedType();
		$enumType->setName(self::ENUM_NAME);
		$enumType->setDescription(self::ENUM_DESC);

		$enumTypeFragment = new EnumFragmentGenerator();
		$enumTypeFragment->setEnumType($enumType);

		return $enumTypeFragment;
	}

}