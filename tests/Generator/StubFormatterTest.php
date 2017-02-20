<?php


namespace GraphQLGen\Tests\Generator;


use GraphQLGen\Generator\StubFormatter;

class StubFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;

	public function setUp() {
		// Config: tab size: 4, use tabs: no
		$this->_formatter = new StubFormatter(true, 4);
	}

	public function test_GivenNoStringArrayWithOneEntry_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => 1, ];";
		$expectedString =
"[
    'test' => 1,
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenArrayWithStringWithOneArray_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => 'This is a string', ];";
		$expectedString =
"[
    'test' => 'This is a string',
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenArrayWithThreeSimpleEntries_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'entry1' => 'This is a string', 'entry2' => 'This is the second entry', 'entry3' => 'and third entry',];";
		$expectedString =
"[
    'entry1' => 'This is a string',
    'entry2' => 'This is the second entry',
    'entry3' => 'and third entry',
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenMultiLevelArray_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => [ 'level2' => 'second level' ] ];";
		$expectedString =
"[
    'test' => [
        'level2' => 'second level'
    ]
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenMultiLevelArrayWithMultipleValues_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => [ 'level2' => 'second level', 'level' => 3 ], 'data' => '222' ];";
		$expectedString =
"[
    'test' => [
        'level2' => 'second level',
        'level' => 3
    ],
    'data' => '222'
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenEmptyArray_formatArray_WillFormatCorectly() {
		$initialString = "[];";
		$expectedString =
"[
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenBackslashesInStrContext_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => \"\\\"Hello world\" ];";
		$expectedString =
"[
    'test' => \"\\\"Hello world\"
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenBackslashesInStrContextWithNewlineToken_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => \"\\\",Hello world[\" ];";
		$expectedString =
"[
    'test' => \"\\\",Hello world[\"
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
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

		$retVal = $this->_formatter->guessIndentsCount($string);

		$this->assertEquals(2, $retVal);
	}
}