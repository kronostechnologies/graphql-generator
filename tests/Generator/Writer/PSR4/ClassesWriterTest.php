<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\ClassFormatter;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\ObjectTypeContent;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Generator\Writer\Namespaced\ClassesFactory;
use GraphQLGen\Generator\Writer\Namespaced\ClassesWriter;
use GraphQLGen\Generator\Writer\Namespaced\ClassMapper;
use GraphQLGen\Generator\Writer\Namespaced\ClassStubFile;
use GraphQLGen\Generator\Writer\Namespaced\NamespacedWriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class ClassesWriterTest extends \PHPUnit_Framework_TestCase {
	const CLASS_NAME = 'ClassNameDummy';
	const CLASS_QUALIFIER = 'class';
	const PARENT_CLASS_NAME = 'AParentClass';
	const USED_TRAIT_1 = 'FirstTrait';
	const USED_TRAIT_2 = 'SecondTrait';
	/**
	 * @var NamespacedWriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_writerContext;

	/**
	 * @var ClassMapper|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classMapper;

	/**
	 * @var ClassesWriter
	 */
	protected $_classesWriter;

	/**
	 * @var ClassFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_classFormatter;

	/**
	 * @var ObjectType|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_singleClassMock;

	/**
	 * @var ObjectTypeContent|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_contentCreator;

	/**
	 * @var ClassStubFile|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFile;

	/**
	 * @var ClassesFactory|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_factory;

	public function setUp() {;
		$this->_stubFile = $this->createMock(ClassStubFile::class);

		$this->_factory = $this->createMock(ClassesFactory::class);
		$this->_factory->method('createStubFile')->willReturn($this->_stubFile);

		$this->_contentCreator = $this->createMock(ObjectTypeContent::class);
		$this->_contentCreator->method('getClassName')->willReturn(self::CLASS_NAME);
		$this->_contentCreator->method('getParentClassName')->willReturn(self::PARENT_CLASS_NAME);

		$this->_singleClassMock = $this->createMock(ObjectType::class);
		$this->_singleClassMock->method('getContentCreator')->willReturn($this->_contentCreator);
		$this->_singleClassMock->method('getDependencies')->willReturn([]);
		$this->_singleClassMock->method('getClassQualifier')->willReturn(self::CLASS_QUALIFIER);
		$this->_singleClassMock->method('getUsedTraits')->willReturn([self::USED_TRAIT_1, self::USED_TRAIT_2]);

		$this->_classFormatter = $this->createMock(ClassFormatter::class);

		$this->_writerContext = $this->createMock(NamespacedWriterContext::class);
		$this->_writerContext->method('getConfiguredClassFormatter')->willReturn($this->_classFormatter);

		$this->_classMapper = $this->createMock(ClassMapper::class);

		$this->_classesWriter = new ClassesWriter($this->_factory);
		$this->_classesWriter->setWriterContext($this->_writerContext);
		$this->_classesWriter->setClassMapper($this->_classMapper);
	}

	public function test_GivenMultipleClasses_writeClasses_WillWriteAsManyFiles() {
		$this->GivenMultipleClasses();

		$this->_writerContext->expects($this->exactly(3))->method('writeFile');

		$this->_classesWriter->writeClasses();
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetContentCreator() {
		$class = $this->GivenSingleClass();

		$this->_singleClassMock->expects($this->once())->method('getContentCreator');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillFetchFormatter() {
		$class = $this->GivenSingleClass();

		$this->_writerContext->expects($this->once())->method('getConfiguredClassFormatter');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillReadStubFileContent() {
		$class = $this->GivenSingleClass();

		$this->_writerContext->expects($this->once())->method('readFileContentToString');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetContent() {
		$class = $this->GivenSingleClass();

		$this->_contentCreator->expects($this->once())->method('getContent');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetVariables() {
		$class = $this->GivenSingleClass();

		$this->_contentCreator->expects($this->once())->method('getVariables');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetDependencies() {
		$class = $this->GivenSingleClass();

		$this->_singleClassMock->expects($this->once())->method('getDependencies');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetNamespace() {
		$class = $this->GivenSingleClass();

		$this->_contentCreator->expects($this->any())->method('getNamespace');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClassAndEmptyNamespace_writeIndividualClass_WillStripNamespace() {
		$class = $this->GivenSingleClass();
		$this->_contentCreator->method('getNamespace')->willReturn('');

		$this->_stubFile->expects($this->once())->method('removeNamespace');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClassAndFilledNamespace_writeIndividualClass_WontStripNamespace() {
		$class = $this->GivenSingleClass();
		$this->_contentCreator->method('getNamespace')->willReturn('ANamespace');

		$this->_stubFile->expects($this->never())->method('removeNamespace');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillWriteClassName() {
		$class = $this->GivenSingleClass();

		$this->_stubFile->expects($this->once())->method('writeClassName')->with(self::CLASS_NAME);

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillWriteParentClassName() {
		$class = $this->GivenSingleClass();

		$this->_stubFile->expects($this->once())->method('writeExtendsClassName')->with(self::PARENT_CLASS_NAME);

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillWriteUsedTraits() {
		$class = $this->GivenSingleClass();

		$this->_stubFile->expects($this->once())->method('writeUsedTraits')->with([self::USED_TRAIT_1, self::USED_TRAIT_2]);

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillWriteClassQualifier() {
		$class = $this->GivenSingleClass();

		$this->_stubFile->expects($this->once())->method('writeClassQualifier')->with(self::CLASS_QUALIFIER);

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillMakeFileDirectory() {
		$class = $this->GivenSingleClass();

		$this->_writerContext->expects($this->once())->method('makeFileDirectory');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSingleClass_writeIndividualClass_WillGetStubFileContent() {
		$class = $this->GivenSingleClass();

		$this->_stubFile->expects($this->once())->method('getFileContent');

		$this->_classesWriter->writeIndividualClass($class);
	}

	public function test_GivenSetClassWriter_getClassMapper_WillReturnClassMapper() {
		// Classmapper is already set, set it again
		$this->_classesWriter->setClassMapper($this->_classMapper);

		$retVal = $this->_classesWriter->getClassMapper();

		$this->assertEquals($this->_classMapper, $retVal);
	}

	protected function GivenMultipleClasses() {
		$this->_classMapper->method('getClasses')->willReturn(
			[
				$this->_singleClassMock,
				$this->_singleClassMock,
				$this->_singleClassMock
			]
		);
	}

	protected function GivenSingleClass() {
		return $this->_singleClassMock;
	}

}