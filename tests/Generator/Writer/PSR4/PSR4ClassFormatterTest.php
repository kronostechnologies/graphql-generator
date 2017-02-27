<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\GeneratorArrayFormatter;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4ClassFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4StubFile;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4ClassFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var StubFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_formatterMock;

	/**
	 * @var PSR4StubFile|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_stubFileMock;

	/**
	 * @var PSR4ClassFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_givenClassFormatter;

	/**
	 * @var GeneratorArrayFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_arrayFormatterMock;

	public function setUp() {
		$this->_arrayFormatterMock = $this->createMock(GeneratorArrayFormatter::class);

		$this->_formatterMock = $this->createMock(StubFormatter::class);
		$this->_formatterMock->arrayFormatter = $this->_arrayFormatterMock;

		$this->_stubFileMock = $this->createMock(PSR4StubFile::class);

		$this->_givenClassFormatter = new PSR4ClassFormatter(
			$this->_formatterMock,
			$this->_stubFileMock
		);
	}

	public function test_GivenClassFormatter_getFormattedTypeDefinition_WillGetTypeDefinitionDeclarationLine() {
		$this
			->_stubFileMock
			->expects($this->once())
			->method('getTypeDefinitionDeclarationLine');

		$this->_givenClassFormatter->getFormattedTypeDefinition(null);
	}


	public function test_GivenClassFormatter_getFormattedTypeDefinition_WillGuessIndentCount() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('guessIndentsCount');

		$this->_givenClassFormatter->getFormattedTypeDefinition(null);
	}

	public function test_GivenClassFormatter_getFormattedTypeDefinition_WillFormatArray() {
		$this
			->_arrayFormatterMock
			->expects($this->once())
			->method('formatArray');

		$this->_givenClassFormatter->getFormattedTypeDefinition(null);
	}

	public function test_GivenClassFormatter_getFormattedVariablesDeclaration_WillGetVariablesDeclarationLine() {
		$this
			->_stubFileMock
			->expects($this->once())
			->method('getVariablesDeclarationLine');

		$this->_givenClassFormatter->getFormattedVariablesDeclaration(null);
	}

	public function test_GivenClassFormatter_getFormattedVariablesDeclaration_WillGuessIndentCount() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('guessIndentsCount');

		$this->_givenClassFormatter->getFormattedVariablesDeclaration(null);
	}

	public function test_GivenClassFormatter_getFormattedVariablesDeclaration_WillIndent() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('indent');

		$this->_givenClassFormatter->getFormattedVariablesDeclaration(null);
	}
}