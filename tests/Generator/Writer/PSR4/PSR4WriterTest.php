<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;
use GraphQLGen\Generator\Writer\PSR4\ClassesFactory;
use GraphQLGen\Generator\Writer\PSR4\ClassesWriter;
use GraphQLGen\Generator\Writer\PSR4\ClassMapper;
use GraphQLGen\Generator\Writer\PSR4\PSR4Writer;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4WriterTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'ScalarType';
	const BASE_NS = 'A\\Base\\Namespace';

	/**
	 * @var PSR4WriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_psr4WriterContext;

	/**
	 * @var PSR4Writer
	 */
	protected $_psr4Writer;

	/**
	 * @var ClassComposer|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classComposer;

	/**
	 * @var ClassesFactory|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_factory;

	/**
	 * @var ClassMapper|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classMapper;

	/**
	 * @var TypeStore|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_typeStore;

	/**
	 * @var PSR4Writer|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classesWriter;

	public function setUp() {
		$this->_classMapper = $this->createMock(ClassMapper::class);
		$this->_classesWriter = $this->createMock(ClassesWriter::class);
		$this->_typeStore = $this->createMock(TypeStore::class);

		$this->_classComposer = $this->createMock(ClassComposer::class);
		$this->_classComposer->method('getClassMapper')->willReturn($this->_classMapper);

		$this->_psr4WriterContext = $this->createMock(PSR4WriterContext::class);
		$this->_psr4WriterContext->formatter = new StubFormatter();

		$this->_factory = $this->createMock(ClassesFactory::class);
		$this->_factory->method('createClassComposer')->willReturn($this->_classComposer);
		$this->_factory->method('createTypeStoreClass')->willReturn($this->_typeStore);
		$this->_factory->method('createClassMapper')->willReturn($this->_classMapper);
		$this->_factory->method('createClassesWriter')->willReturn($this->_classesWriter);

		$this->_psr4Writer = new PSR4Writer($this->_psr4WriterContext, $this->_factory);
		$this->_psr4Writer->setClassComposer($this->_classComposer);
	}


	public function test_GivenNothing_initialize_WillCreateDirectoryStructure() {
		$this->_psr4WriterContext->expects($this->any())->method('createDirectoryIfNonExistant');

		$this->_psr4Writer->initialize();
	}

	public function test_GivenSettings_initialize_WillSetClassMapper() {
		$this->_psr4WriterContext->namespace = self::BASE_NS;

		$this->_classMapper->expects($this->once())->method('setBaseNamespace')->with(self::BASE_NS);
		$this->_classMapper->expects($this->once())->method('setInitialMappings');

		$this->_psr4Writer->initialize();
	}

	public function test_GivenSettings_initialize_WillGenerateTypeStore() {
		$this->_classComposer->expects($this->once())->method('initializeTypeStore');

		$this->_psr4Writer->initialize();
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillGetProperStubFileName() {
		$scalarType = $this->GivenScalarType();

		$this->_classComposer->expects($this->once())->method('generateTypeDefinitionForFragmentGenerator')->with($scalarType);

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillGenerateClass() {
		$scalarType = $this->GivenScalarType();

		$this->_classComposer->expects($this->once())->method('generateTypeDefinitionForFragmentGenerator')->with($scalarType);

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarTypeAndGeneratorSupportsResolver_generateFileForTypeGenerator_WillGenerateResolver() {
		$scalarType = $this->GivenScalarType();
		$this->_classComposer->method('isFragmentGeneratorForInputType')->willReturn(true);

		$this->_classComposer->expects($this->once())->method('generateResolverForFragmentGenerator')->with($scalarType);

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarTypeAndGeneratorSupportsResolver_generateFileForTypeGenerator_WillGenerateDTO() {
		$scalarType = $this->GivenScalarType();
		$this->_classComposer->method('isFragmentGeneratorForInputType')->willReturn(true);

		$this->_classComposer->expects($this->once())->method('generateDTOForFragmentGenerator')->with($scalarType);

		$this->_psr4Writer->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenNothing_finalize_WillWriteClasses() {
		$this->_classesWriter->expects($this->once())->method('writeClasses');

		$this->_psr4Writer->finalize();
	}

	protected function GivenScalarType() {
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);

		return $scalarTypeFragment;
	}

	protected function GivenAtLeastOneResolveToken() {
		$this->_classComposer->method('getAllResolvedTokens')->willReturn([ '123' => 'NS\123']);
	}

}