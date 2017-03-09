<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\PSR4\ClassStubFile;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class ClassStubFileTest extends \PHPUnit_Framework_TestCase {
	const STUB_FILE_LINE_1 = "FirstLine;";
	const STUB_FILE_LINE_2 = "namespace LocalNamespace;";
	const STUB_FILE_LINE_3 = "class ClassName extends ParentClass {";
	const STUB_FILE_LINE_4 = "// @generate:Variables";

	const NAMESPACE_NEW = "TestNamespace";
	const CLASS_NEW = "NewClass";
	const EXTENDS_CLASS_NEW = "NewParentClass";

	const VARIABLES_DECLARATIONS = "var aVariable = 123;";


	/**
	 * @var ClassStubFile
	 */
	protected $_givenStubFile;

	/**
	 * @var PSR4WriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_context;

	public function setUp() {
		$this->_context = $this->createMock(PSR4WriterContext::class);

		$this->_givenStubFile = new ClassStubFile($this->_context);
		$stubContent = file_get_contents(getcwd() . '/tests/Mocks/Stubs/PSR4StubFileTest.stub');
		$this->_givenStubFile->setFileContent($stubContent);
	}

	public function test_GivenStubFile_getNamespaceDeclarationLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getNamespaceDeclarationLine();

		$this->assertEquals(self::STUB_FILE_LINE_2, $retVal);
	}

	public function test_GivenStubFile_getClassNameLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getClassNameLine();

		$this->assertEquals(self::STUB_FILE_LINE_3, $retVal);
	}

	public function test_GivenStubFile_getExtendsClassNameLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getExtendsClassNameLine();

		$this->assertEquals(self::STUB_FILE_LINE_3, $retVal);
	}

	public function test_GivenStubFile_getVariablesDeclarationLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getVariablesDeclarationLine();

		$this->assertEquals(self::STUB_FILE_LINE_4, $retVal);
	}


	public function test_GivenStubFile_writeNamespace_WillReplaceRightLine() {
		$this->_givenStubFile->writeNamespace(self::NAMESPACE_NEW);
		$retVal = $this->_givenStubFile->getFileContent();

		$this->assertContains(self::NAMESPACE_NEW, $retVal);
	}

	public function test_GivenStubFile_writeClassName_WillReplaceRightLine() {
		$this->_givenStubFile->writeClassName(self::CLASS_NEW);
		$retVal = $this->_givenStubFile->getFileContent();

		$this->assertContains(self::CLASS_NEW, $retVal);
	}

	public function test_GivenStubFile_writeExtendsClassName_WillReplaceRightLine() {
		$this->_givenStubFile->writeExtendsClassName(self::EXTENDS_CLASS_NEW);
		$retVal = $this->_givenStubFile->getFileContent();

		$this->assertContains(self::EXTENDS_CLASS_NEW, $retVal);
	}

	public function test_GivenStubFile_writeVariablesDeclarations_WillReplaceRightLine() {
		$this->_givenStubFile->writeVariablesDeclarations(self::VARIABLES_DECLARATIONS);
		$retVal = $this->_givenStubFile->getFileContent();

		$this->assertContains(self::VARIABLES_DECLARATIONS, $retVal);
	}
}