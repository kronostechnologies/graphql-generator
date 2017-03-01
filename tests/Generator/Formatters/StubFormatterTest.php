<?php


namespace GraphQLGen\Tests\Generator\Formatters;


use GraphQLGen\Generator\Formatters\StubFormatter;

class StubFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;

	public function setUp() {
		// Config: tab size: 4, use tabs: no
		$this->_formatter = new StubFormatter(true, 4);
	}

	public function test_GivenUnindentedLine_indent_WillIndentTextCorrectly() {
		$initialString = "unindentedText = 123;";
		$expectedString = "    unindentedText = 123;";

		$retVal = $this->_formatter->indent($initialString, 1);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenUnindentedLines_indent_WillIndentTextCorrectly() {
		$initialString = "unindentedText = 123;
unindentedText2 = '333';
unindentedText3 = 'asbas';";
		$expectedString = "    unindentedText = 123;
    unindentedText2 = '333';
    unindentedText3 = 'asbas';";

		$retVal = $this->_formatter->indent($initialString, 1);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenIndentedText_guess_WillGuessNumberOfIndentsCorrectly() {
		$string = "        testText"; // 2

		$retVal = $this->_formatter->guessIndentsSize($string);

		$this->assertEquals(2, $retVal);
	}
}