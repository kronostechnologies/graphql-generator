<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassComposer;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\TypeStore;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassesFactory;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassesWriter;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassMapper;
use GraphQLGen\Old\Generator\Writer\Namespaced\NamespacedWriter;
use GraphQLGen\Old\Generator\Writer\Namespaced\NamespacedWriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4WriterTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'ScalarType';
	const BASE_NS = 'A\\Base\\Namespace';

	/**
	 * @var NamespacedWriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_nsWriterContext;

	/**
	 * @var NamespacedWriter
	 */
	protected $_nsWriter;

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
	 * @var NamespacedWriter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classesWriter;

	public function setUp() {
		$this->_classMapper = $this->createMock(ClassMapper::class);
		$this->_classesWriter = $this->createMock(ClassesWriter::class);
		$this->_typeStore = $this->createMock(TypeStore::class);

		$this->_classComposer = $this->createMock(ClassComposer::class);
		$this->_classComposer->method('getClassMapper')->willReturn($this->_classMapper);

		$this->_nsWriterContext = $this->createMock(NamespacedWriterContext::class);
		$this->_nsWriterContext->formatter = new StubFormatter();

		$this->_factory = $this->createMock(ClassesFactory::class);
		$this->_factory->method('createClassComposer')->willReturn($this->_classComposer);
		$this->_factory->method('createTypeStoreClass')->willReturn($this->_typeStore);
		$this->_factory->method('createClassMapper')->willReturn($this->_classMapper);
		$this->_factory->method('createClassesWriter')->willReturn($this->_classesWriter);

		$this->_nsWriter = new NamespacedWriter($this->_nsWriterContext, $this->_factory);
		$this->_nsWriter->setClassComposer($this->_classComposer);
	}


	public function test_GivenNothing_initialize_WillCreateDirectoryStructure() {
		$this->_nsWriterContext->expects($this->any())->method('createDirectoryIfNonExistant');

		$this->_nsWriter->initialize();
	}

	public function test_GivenSettings_initialize_WillSetClassMapper() {
		$this->_nsWriterContext->namespace = self::BASE_NS;

		$this->_classMapper->expects($this->once())->method('setBaseNamespace')->with(self::BASE_NS);
		$this->_classMapper->expects($this->once())->method('setInitialMappings');

		$this->_nsWriter->initialize();
	}

	public function test_GivenSettings_initialize_WillGenerateTypeStore() {
		$this->_classComposer->expects($this->once())->method('initializeTypeStore');

		$this->_nsWriter->initialize();
	}

	public function test_GivenSettings_initialize_WillGenerateResolverFactory() {
		$this->_classComposer->expects($this->once())->method('initializeResolverFactory');

		$this->_nsWriter->initialize();
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillGetProperStubFileName() {
		$scalarType = $this->GivenScalarType();

		$this->_classComposer->expects($this->once())->method('generateTypeDefinitionForFragmentGenerator')->with($scalarType);

		$this->_nsWriter->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarType_generateFileForTypeGenerator_WillGenerateClass() {
		$scalarType = $this->GivenScalarType();

		$this->_classComposer->expects($this->once())->method('generateTypeDefinitionForFragmentGenerator')->with($scalarType);

		$this->_nsWriter->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarTypeAndGeneratorSupportsResolver_generateFileForTypeGenerator_WillGenerateResolver() {
		$scalarType = $this->GivenScalarType();
		$this->_classComposer->method('isFragmentGeneratorForInputType')->willReturn(true);

		$this->_classComposer->expects($this->once())->method('generateResolverForFragmentGenerator')->with($scalarType);

		$this->_nsWriter->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenScalarTypeAndGeneratorSupportsResolver_generateFileForTypeGenerator_WillGenerateDTO() {
		$scalarType = $this->GivenScalarType();
		$this->_classComposer->method('isFragmentGeneratorForInputType')->willReturn(true);

		$this->_classComposer->expects($this->once())->method('generateDTOForFragmentGenerator')->with($scalarType);

		$this->_nsWriter->generateFileForTypeGenerator($scalarType);
	}

	public function test_GivenNothing_finalize_WillWriteClasses() {
		$this->_classesWriter->expects($this->once())->method('writeClasses');

		$this->_nsWriter->finalize();
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