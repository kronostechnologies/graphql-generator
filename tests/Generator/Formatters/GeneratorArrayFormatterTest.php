<?php


namespace GraphQLGen\Tests\Generator\Formatters;


use GraphQLGen\Generator\Formatters\GeneratorArrayFormatter;

class GeneratorArrayFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var GeneratorArrayFormatter
	 */
	protected $_formatter;

	public function setUp() {
		// Config: tab size: 4, use tabs: no
		$this->_formatter = new GeneratorArrayFormatter(true, 4);
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

	public function test_GivenFunctionWithParamsInArray_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => function (\$param1, \$param2) { \$this->localFunction(\"123\", \$param1, \$param2); } ];";
		$expectedString =
			"[
    'test' => function (\$param1, \$param2) {
        \$this->localFunction(\"123\", \$param1, \$param2);
    }
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}

	public function test_GivenFunctionWithParamsInArrayWithStringDefaultParam_formatArray_WillFormatCorrectly() {
		$initialString = "[ 'test' => function (\$param1, \$param2 = \"defaultparam\", \$param3 = 0) { \$this->localFunction(\"123\", \$param1, \$param2); } ];";
		$expectedString =
			"[
    'test' => function (\$param1, \$param2 = \"defaultparam\", \$param3 = 0) {
        \$this->localFunction(\"123\", \$param1, \$param2);
    }
];";

		$retVal = $this->_formatter->formatArray($initialString, 0);

		$this->assertEquals($expectedString, $retVal);
	}
}