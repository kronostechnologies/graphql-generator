<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\PSR4\PSR4StubFile;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4StubFileTest extends \PHPUnit_Framework_TestCase {
	const STUB_FILE_LINE_1 = "FirstLine;";
	const STUB_FILE_LINE_2 = "namespace DummyNamespace;";
	const STUB_FILE_LINE_3 = "class DummyClass {";
	const STUB_FILE_LINE_4 = "'VariablesDeclarations';";

	const NAMESPACE_NEW = "TestNamespace";
	const CLASS_NEW = "NewClass";
	const VARIABLES_DECLARATIONS = "var aVariable = 123;";

	/**
	 * @var PSR4StubFile
	 */
	protected $_givenStubFile;

	/**
	 * @var PSR4WriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_context;

	public function setUp() {
		$this->_context = $this->createMock(PSR4WriterContext::class);

		$this->_givenStubFile = new PSR4StubFile($this->_context);
		$stubContent = file_get_contents(getcwd() . '/tests/Mocks/Stubs/PSR4StubFileTest.stub');
		$this->_givenStubFile->setContent($stubContent);
	}

	public function test_GivenStubFile_getNamespaceDeclarationLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getNamespaceDeclarationLine();

		$this->assertEquals(self::STUB_FILE_LINE_2, $retVal);
	}

	public function test_GivenStubFile_getDummyClassNameLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getDummyClassNameLine();

		$this->assertEquals(self::STUB_FILE_LINE_3, $retVal);
	}

	public function test_GivenStubFile_getVariablesDeclarationLine_WillReturnRightLine() {
		$retVal = $this->_givenStubFile->getVariablesDeclarationLine();

		$this->assertEquals(self::STUB_FILE_LINE_4, $retVal);
	}


	public function test_GivenStubFile_writeNamespace_WillReplaceRightLine() {
		$this->_givenStubFile->writeNamespace(self::NAMESPACE_NEW);
		$retVal = $this->_givenStubFile->getContent();

		$this->assertContains(self::NAMESPACE_NEW, $retVal);
	}

	public function test_GivenStubFile_writeClassName_WillReplaceRightLine() {
		$this->_givenStubFile->writeClassName(self::CLASS_NEW);
		$retVal = $this->_givenStubFile->getContent();

		$this->assertContains(self::CLASS_NEW, $retVal);
	}

	public function test_GivenStubFile_writeVariablesDeclarations_WillReplaceRightLine() {
		$this->_givenStubFile->writeVariablesDeclarations(self::VARIABLES_DECLARATIONS);
		$retVal = $this->_givenStubFile->getContent();

		$this->assertContains(self::VARIABLES_DECLARATIONS, $retVal);
	}
}