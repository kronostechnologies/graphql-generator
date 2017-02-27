<?php


namespace GraphQLGen\Tests\Generator\Writer;


use GraphQLGen\Generator\Writer\StubFile;

class StubFileTest extends \PHPUnit_Framework_TestCase {
	const STUB_LINE_1 = "FirstLine = 1; Set 1;";
	const STUB_LINE_2 = "SecondLine = 2; Set 2;";
	const STUB_LINE_3 = "ThirdLine = 3; Set 1;";
	const STUB_LINE_4 = "FourthLine = 4; Set 2;";
	const STUB_LINE_5 = "SomeUsesGoesHere 'UsesDeclarations';";
	const STUB_LINE_6 = "TypeDefinitionGoesHere 'TypeDefinitionDeclaration';";
	const ALTERED_TEXT = "Altered text here";
	const ALTERED_LINE_1 = "FirstLine Altered text here; Set 1;";
	const ALTERED_LINE_5 = "SomeUsesGoesHere Altered text here";
	const ALTERED_LINE_6 = "TypeDefinitionGoesHere Altered text here";
	const STUB_LINE_1_SUB = "= 1";
	const STUB_LINE_2_SUB = "= 2";
	const STUB_LINE_SUB_SET_1 = "Set 1;";

	/**
	 * @var StubFile
	 */
	protected $_givenStubFile;

	public function setUp() {
		$this->_givenStubFile = new StubFile();
		$this->_givenStubFile->loadFromFile(getcwd() . '/tests/Mocks/Stubs/StubFileTest.stub');
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
		$retVal = $this->_givenStubFile->getUsesDeclarationLine();

		$this->assertEquals(self::STUB_LINE_5, $retVal);
	}

	public function test_GivenStubFile_getTypeDefinitionDeclarationLine_WillReturnCorrectLine() {
		$retVal = $this->_givenStubFile->getTypeDefinitionDeclarationLine();

		$this->assertEquals(self::STUB_LINE_6, $retVal);
	}

	public function test_GivenStubFile_getContent_WillReturnCorrectFileData() {
		$retVal = $this->_givenStubFile->getContent();

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
		$this->_givenStubFile->writeUsesDeclaration(self::ALTERED_TEXT);
		$retVal = $this->_givenStubFile->getLineWithText(self::ALTERED_TEXT);

		$this->assertEquals(self::ALTERED_LINE_5, $retVal);
	}

	public function test_GivenStubFile_writeTypeDefinition_WillAlterUsesDeclaration() {
		$this->_givenStubFile->writeTypeDefinition(self::ALTERED_TEXT);
		$retVal = $this->_givenStubFile->getLineWithText(self::ALTERED_TEXT);

		$this->assertEquals(self::ALTERED_LINE_6, $retVal);
	}
}