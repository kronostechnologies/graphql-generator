<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\ClassFormatter;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;
use GraphQLGen\Generator\Writer\PSR4\ClassesWriter;
use GraphQLGen\Generator\Writer\PSR4\ClassMapper;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class ClassesWriterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var PSR4WriterContext|PHPUnit_Framework_MockObject_MockObject
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
	 * @var SingleClass|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_singleClassMock;

	public function setUp() {;
		$this->_singleClassMock = $this->createMock(SingleClass::class);

		$this->_classFormatter = $this->createMock(ClassFormatter::class);

		$this->_writerContext = $this->createMock(PSR4WriterContext::class);
		$this->_writerContext->method('getConfiguredClassFormatter')->willReturn($this->_classFormatter);

		$this->_classMapper = $this->createMock(ClassMapper::class);

		$this->_classesWriter = new ClassesWriter();
		$this->_classesWriter->setWriterContext($this->_writerContext);
		$this->_classesWriter->setClassMapper($this->_classMapper);
	}

	public function test_GivenMultipleClasses_writeClasses_WillWriteAsManyFiles() {
		$this->GivenMultipleClasses();

		$this->_writerContext->expects($this->exactly(3))->method('writeFile');

		$this->_classesWriter->writeClasses();
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

	public function test_GivenSingleClass_writeIndividualClass_WillGetContentCreator() {
		$class = $this->GivenSingleClass();

		$this->_singleClassMock->expects($this->once())->method('getContentCreator');

		$this->_classesWriter->writeIndividualClass($class);
	}
}