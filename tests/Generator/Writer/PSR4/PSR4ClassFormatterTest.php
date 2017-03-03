<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\GeneratorArrayFormatter;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4ClassFormatter;
use GraphQLGen\Generator\Writer\PSR4\ClassStubFile;
use PHPUnit_Framework_MockObject_MockObject;

class PSR4ClassFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var StubFormatter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_formatterMock;

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

		$this->_givenClassFormatter = new PSR4ClassFormatter($this->_formatterMock);
	}

	public function test_GivenClassFormatter_getFormattedTypeDefinition_WillGuessIndentSize() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('guessIndentsSize');

		$this->_givenClassFormatter->getFormattedTypeDefinition(null, null);
	}

	public function test_GivenClassFormatter_getFormattedTypeDefinition_WillFormatArray() {
		$this
			->_arrayFormatterMock
			->expects($this->once())
			->method('formatArray');

		$this->_givenClassFormatter->getFormattedTypeDefinition(null, null);
	}

	public function test_GivenClassFormatter_getFormattedVariablesDeclaration_WillGuessIndentSize() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('guessIndentsSize');

		$this->_givenClassFormatter->getFormattedVariablesDeclaration(null, null);
	}

	public function test_GivenClassFormatter_getFormattedVariablesDeclaration_WillIndent() {
		$this
			->_formatterMock
			->expects($this->once())
			->method('indent');

		$this->_givenClassFormatter->getFormattedVariablesDeclaration(null, null);
	}
}