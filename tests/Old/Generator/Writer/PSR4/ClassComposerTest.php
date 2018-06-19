<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4;


use GraphQLGen\Old\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassComposer;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\DTO;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\Resolver;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ResolverFactory;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\TypeStore;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassesFactory;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassMapper;
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

	/**
	 * @var ResolverFactory|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_resolverFactoryMock;


	public function setUp() {
		$this->_dtoTypeMock = $this->createMock(DTO::class);
		$this->_objTypeMock = $this->createMock(ObjectType::class);
		$this->_objTypeMock->method('getParentClassName')->willReturn(self::PARENT_CLASS_NAME);
		$this->_resolverMock = $this->createMock(Resolver::class);
		$this->_typeStoreMock = $this->createMock(TypeStore::class);
		$this->_resolverFactoryMock = $this->createMock(ResolverFactory::class);

		$this->_factory = $this->createMock(ClassesFactory::class);
		$this->_factory->method('createDTOClassWithFragmentGenerator')->willReturn($this->_dtoTypeMock);
		$this->_factory->method('createObjectTypeClassWithFragmentGenerator')->willReturn($this->_objTypeMock);
		$this->_factory->method('createResolverClassWithFragmentGenerator')->willReturn($this->_resolverMock);
		$this->_factory->method('createTypeStoreClass')->willReturn($this->_typeStoreMock);
		$this->_factory->method('createResolverFactoryClass')->willReturn($this->_resolverFactoryMock);

		$this->_classMapper = $this->createMock(ClassMapper::class);
		$this->_classMapper->method('getTypeStore')->willReturn($this->_typeStore);

		$this->_classesComposer = new ClassComposer($this->_factory);
		$this->_classesComposer->setClassMapper($this->_classMapper);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillCreateObjectTypeClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createObjectTypeClassWithFragmentGenerator');

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillFetchNamespaceWithType() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper->expects($this->once())->method('getNamespaceForFragmentGenerator')->with($givenType);

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillFetchParentDependenciesWithType() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper->expects($this->once())->method('getParentDependencyForFragmentGenerator')->with($givenType);

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateClassForGenerator_WillAddCorrectDependencies() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_objTypeMock->method('getFragmentGenerator')->willReturn($givenType);
		$this->_objTypeMock
			->expects($this->exactly(3))
			->method('addDependency')
			->with($this->logicalOr(
				ClassComposer::TYPE_STORE_CLASS_NAME,
				ClassComposer::TYPE_CLASS_NAME,
				$this->_objTypeMock->getParentClassName()
			));

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectTypeWithGeneratorClassReturned_generateClassForGenerator_WillAddTypeNameDependency() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_typeStoreMock
			->expects($this->any())
			->method('addDependency')
			->with($givenType->getName());

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectTypeWithGeneratorClassReturned_generateClassForGenerator_WillAddTypeToResolverFactory() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_resolverFactoryMock
			->expects($this->any())
			->method('addResolveableTypeImplementation');

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenEnumTypeWithGeneratorClassReturned_generateClassForGenerator_WillAddTypeToResolverFactory() {
		$givenType = $this->GivenEnumObjectType();

		$this->_resolverFactoryMock
			->expects($this->never())
			->method('addResolveableTypeImplementation');

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectTypeWithGeneratorClassReturned_generateClassForGenerator_ClassMapperWillMapClassNameToValueCorrectly() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapDependencyNameToClass')
			->with($givenType->getName(), $this->_objTypeMock);

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenInterfaceObjectType_generateClassForGenerator_WillAddCorrectDependencies() {
		$givenType = $this->GivenInterfaceObjectType();

		$this->_objTypeMock->method('getFragmentGenerator')->willReturn($givenType);
		$this->_objTypeMock
			->expects($this->exactly(3))
			->method('addDependency')
			->with($this->logicalOr(
				ClassComposer::TYPE_STORE_CLASS_NAME,
				ClassComposer::TYPE_CLASS_NAME,
				$this->_objTypeMock->getParentClassName()
			));

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
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

		$this->_classesComposer->generateTypeDefinitionForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_WillGenerateResolverClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createResolverClassWithFragmentGenerator')
			->with($givenType);

		$this->_classesComposer->generateResolverForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_WillResolveNamespaceForClass() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('getResolverNamespaceFromGenerator')
			->with($givenType);

		$this->_classesComposer->generateResolverForFragmentGenerator($givenType);
	}

	public function test_GivenObjectType_generateResolverForGenerator_ClassMapperWillMapClassNameToValueCorrectly() {
		$givenType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapDependencyNameToClass')
			->with($this->_resolverMock->getClassName(), $this->_resolverMock);

		$this->_classesComposer->generateResolverForFragmentGenerator($givenType);
	}

	public function test_GivenAnything_initializeTypeStore_WillGenerateTypeStoreClass() {
		$this->_factory
			->expects($this->once())
			->method('createTypeStoreClass');

		$this->_classesComposer->initializeTypeStore();
	}

	public function test_GivenAnything_initializeTypeStore_WillFetchBaseClassMapperNamespace() {
		$this->_classMapper
			->expects($this->once())
			->method('getBaseNamespace');

		$this->_classesComposer->initializeTypeStore();
	}

	public function test_GivenAnything_initializeTypeStore_WillSetClassMapperTypeStore() {
		$this->_classMapper
			->expects($this->once())
			->method('setTypeStore')
			->with($this->_typeStoreMock);

		$this->_classesComposer->initializeTypeStore();
	}

	public function test_GivenAnything_initializeTypeStore_ClassMapperWillMapClassNameToValueCorrectly() {
		$this->_classMapper
			->expects($this->once())
			->method('mapDependencyNameToClass')
			->with($this->_typeStoreMock->getClassName(), $this->_typeStoreMock);

		$this->_classesComposer->initializeTypeStore();
	}

	public function test_GivenAnything_initializeTypeStore_WillAddResolverFactoryDependency() {
		$this->_typeStoreMock
			->expects($this->once())
			->method('addDependency')
			->with(ClassComposer::RESOLVER_FACTORY);

		$this->_classesComposer->initializeTypeStore();
	}

	public function test_GivenAnything_initializeResolverFactory_WillGenerateResolverFactoryClass() {
		$this->_factory
			->expects($this->once())
			->method('createResolverFactoryClass');

		$this->_classesComposer->initializeResolverFactory();
	}

	public function test_GivenAnything_initializeResolverFactory_WillSetClassMapperResolverFactory() {
		$this->_classMapper
			->expects($this->once())
			->method('setResolverFactory')
			->with($this->_resolverFactoryMock);

		$this->_classesComposer->initializeResolverFactory();
	}

	public function test_GivenAnything_initializeResolverFactory_ClassMapperWillMapClassNameToValueCorrectly() {
		$this->_classMapper
			->expects($this->once())
			->method('mapDependencyNameToClass')
			->with($this->_resolverFactoryMock->getClassName(), $this->_resolverFactoryMock);

		$this->_classesComposer->initializeResolverFactory();
	}

	public function test_GivenAnything_generateDTOForGenerator_WillCreateDTOClass() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_factory
			->expects($this->once())
			->method('createDTOClassWithFragmentGenerator');

		$this->_classesComposer->generateDTOForFragmentGenerator($givenObjectType);
	}

	public function test_GivenAnything_generateDTOForGenerator_WillSetNamespace() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_dtoTypeMock
			->expects($this->once())
			->method('setNamespace');

		$this->_classesComposer->generateDTOForFragmentGenerator($givenObjectType);
	}

	public function test_GivenObjectTypeWithDependencies_generateDTOForGenerator_WillAddDependencies() {
		$givenObjectType = $this->GivenObjectTypeWithDependencies();

		$this->_dtoTypeMock
			->expects($this->any())
			->method('addDependency');

		$this->_classesComposer->generateDTOForFragmentGenerator($givenObjectType);
	}

	public function test_GivenAnything_generateDTOForGenerator_WillMapClass() {
		$givenObjectType = $this->GivenObjectTypeFragmentGenerator();

		$this->_classMapper
			->expects($this->once())
			->method('mapDependencyNameToClass')
			->with($this->_dtoTypeMock->getClassName(), $this->_dtoTypeMock);;

		$this->_classesComposer->generateDTOForFragmentGenerator($givenObjectType);
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