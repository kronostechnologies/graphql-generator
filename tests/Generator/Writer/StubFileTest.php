<?php


namespace GraphQLGen\Tests\Generator\Writer;


use GraphQLGen\Generator\Writer\StubFile;
use GraphQLGen\Generator\Writer\WriterContext;
use PHPUnit_Framework_MockObject_MockObject;

class StubFileTest extends \PHPUnit_Framework_TestCase {
	const STUB_LINE_1 = "FirstLine = 1; Set 1;";
	const STUB_LINE_2 = "SecondLine = 2; Set 2;";
	const STUB_LINE_3 = "ThirdLine = 3; Set 1;";
	const STUB_LINE_4 = "FourthLine = 4; Set 2;";
	const STUB_LINE_5 = "SomeUsesGoesHere // @generate:Dependencies";
	const STUB_LINE_6 = "TypeDefinitionGoesHere // @generate:Content";
	const ALTERED_TEXT = "Altered text here";
	const ALTERED_LINE_1 = "FirstLine Altered text here; Set 1;";
	const ALTERED_LINE_5 = "Altered text here";
	const ALTERED_LINE_6 = "Altered text here";
	const STUB_LINE_1_SUB = "= 1";
	const STUB_LINE_2_SUB = "= 2";
	const STUB_LINE_SUB_SET_1 = "Set 1;";

	/**
	 * @var StubFile
	 */
	protected $_givenStubFile;

	/**
	 * @var WriterContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_context;

	public function setUp() {
		$this->_context = $this->createMock(WriterContext::class);

		$this->_givenStubFile = new StubFile($this->_context);
		$content = file_get_contents(getcwd() . '/tests/Mocks/Stubs/StubFileTest.stub');
		$this->_givenStubFile->setFileContent($content);
	}

	public function test_GivenStubFile_getLineWithText_WillReturnFirstLine() {
		$retVal = $this->_givenStubFile->getLineWithText(self::STUB_LINE_1_SUB);

		$this->assertEquals(self::STUB_LINE_1, $retVal);
	}

	public function test_GivenStubFile_getLineWithText_WillReturnSecondLine() {
		$retVal = $this->_givenStubFile->getLineWithText(self::STUB_LINE_2_SUB);

		$this->assertEquals(self::STUB_LINE_2, $retVal);
	}

	public function test_GivenStubFile_getLinesWithText_WillReturnCorrectCountOfLines() {
		$retVal = $this->_givenStubFile->getLinesWithText(self::STUB_LINE_SUB_SET_1);

		$this->assertCount(2, $retVal);
	}

	public function test_GivenStubFile_getLinesWithText_WillReturnCorrectLines() {
		$retVal = $this->_givenStubFile->getLinesWithText(self::STUB_LINE_SUB_SET_1);

		$this->assertEquals([self::STUB_LINE_1, self::STUB_LINE_3], $retVal);
	}

	public function test_GivenStubFile_getUsesDeclarationLine_WillReturnCorrectLine() {
		$retVal = $this->_givenStubFile->getDependenciesDeclarationLine();

		$this->assertEquals(self::STUB_LINE_5, $retVal);
	}

	public function test_GivenStubFile_getTypeDefinitionDeclarationLine_WillReturnCorrectLine() {
		$retVal = $this->_givenStubFile->getContentDeclarationLine();

		$this->assertEquals(self::STUB_LINE_6, $retVal);
	}

	public function test_GivenStubFile_getContent_WillReturnCorrectFileData() {
		$retVal = $this->_givenStubFile->getFileContent();

		$this->assertEquals(file_get_contents(getcwd() . '/tests/Mocks/Stubs/StubFileTest.stub'), $retVal);
	}

	public function test_GivenStubFile_getContentAsLines_WillReturnOrderedLines() {
		$retVal = $this->_givenStubFile->getContentAsLines();

		$this->assertEquals([
			self::STUB_LINE_1,
			self::STUB_LINE_2,
			self::STUB_LINE_3,
			self::STUB_LINE_4,
			self::STUB_LINE_5,
			self::STUB_LINE_6,
		], $retVal);
	}

	public function test_GivenStubFile_replaceTextInStub_WillAlterTextCorrectly() {
		$this->_givenStubFile->replaceTextInStub(self::STUB_LINE_1_SUB, self::ALTERED_TEXT);
		$retVal = $this->_givenStubFile->getLineWithText(self::ALTERED_TEXT);

		$this->assertEquals(self::ALTERED_LINE_1, $retVal);
	}

	public function test_GivenStubFile_writeUsesDeclaration_WillAlterTypeDefinition() {
		$this->_givenStubFile->writeDependenciesDeclaration(self::ALTERED_TEXT);
		$retVal = $this->_givenStubFile->getLineWithText(self::ALTERED_TEXT);

		$this->assertEquals(self::ALTERED_LINE_5, $retVal);
	}

	public function test_GivenStubFile_writeTypeDefinition_WillAlterUsesDeclaration() {
		$this->_givenStubFile->writeContent(self::ALTERED_TEXT);
		$retVal = $this->_givenStubFile->getLineWithText(self::ALTERED_TEXT);

		$this->assertEquals(self::ALTERED_LINE_6, $retVal);
	}
}