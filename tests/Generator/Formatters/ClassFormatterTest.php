<?php


namespace GraphQLGen\Tests\Generator\Formatters;


use GraphQLGen\Generator\Formatters\ClassFormatter;


class ClassFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var ClassFormatter
	 */
	protected $_formatter;

	public function setUp() {
		$this->_formatter = new ClassFormatter();
		$this->_formatter->setTabSize(4);
		$this->_formatter->setUseSpaces(true);
	}

	public function test_GivenSimpleFunctionString_format_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenSimpleFunctionString();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenSimpleFunctionString_Expected(), $retVal);
	}

	protected function GivenSimpleFunctionString() {
		return "function test() { echo 'a simple function'; }";
	}


	protected function GivenSimpleFunctionString_Expected() {
		return "function test() {
    echo 'a simple function';
}";
	}

	protected function GivenSimpleFunctionStringWithDefaultIndentLevel_Expected() {
		return "    function test() {
        echo 'a simple function';
    }";
	}

	public function test_GivenVariablesDeclaration_format_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenVariablesDeclaration();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenVariablesDeclaration_Expected(), $retVal);
	}

	protected function GivenVariablesDeclaration() {
		return "private \$var1; protected \$var2; public \$var3;";
	}

	protected function GivenVariablesDeclaration_Expected() {
		return "private \$var1;
protected \$var2;
public \$var3;";
	}

	public function test_GivenFunctionWithIfBlock_format_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenFunctionWithIfBlock();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenFunctionWithIfBlock_Expected(), $retVal);
	}

	protected function GivenFunctionWithIfBlock() {
		return "function conditionalFunction() { if (2 > 1) { echo 'OK'; } else { echo 'Not OK'; } }";
	}

	protected function GivenFunctionWithIfBlock_Expected() {
		return "function conditionalFunction() {
    if (2 > 1) {
        echo 'OK';
    }
    else {
        echo 'Not OK';
    }
}";
	}

	public function test_GivenFullClassDefinition_format_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenFullClassDefinition();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenFullClassDefinition_Expected(), $retVal);
	}

	protected function GivenFullClassDefinition() {
		return "class AFullClass { protected \$internalVariable; function internalFunction() { echo 'Hello world'; } }";
	}

	protected function GivenFullClassDefinition_Expected() {
		return "class AFullClass {
    protected \$internalVariable;
    function internalFunction() {
        echo 'Hello world';
    }
}";
	}

	public function test_GivenSimpleFunctionWithDefaultIndentLevel_format_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenSimpleFunctionString();

		$retVal = $this->_formatter->format($givenString, 1);

		$this->assertEquals($this->GivenSimpleFunctionStringWithDefaultIndentLevel_Expected(), $retVal);
	}

	public function test_GivenMultipleFunctionsDefinitionsEmpty_indent_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenMultipleFunctionsDefinitionsEmpty();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenMultipleFunctionsDefinitionsEmpty_Expected(), $retVal);
	}

	protected function GivenMultipleFunctionsDefinitionsEmpty() {
		return "function firstFunction() { } function secondFunction() { }";
	}

	protected function GivenMultipleFunctionsDefinitionsEmpty_Expected() {
		return "function firstFunction() {
}
function secondFunction() {
}";
	}


	public function test_GivenMultipleFunctionsDefinitions_indent_WillReturnCorrectlyFormattedFunction() {
		$givenString = $this->GivenMultipleFunctionsDefinitions();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenMultipleFunctionsDefinitions_Expected(), $retVal);
	}

	protected function GivenMultipleFunctionsDefinitions() {
		return "function firstFunction() { echo 'test'; } function secondFunction() { \$localVar = '123'; return \$localVar; }";
	}

	protected function GivenMultipleFunctionsDefinitions_Expected() {
		return "function firstFunction() {
    echo 'test';
}
function secondFunction() {
    \$localVar = '123';
    return \$localVar;
}";
	}

	public function test_GivenFunctionDefinitionWithEscapedString_indent_WillEscapeStringCorrectly() {
		$givenString = $this->GivenFunctionDefinitionWithEscapedString();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenFunctionDefinitionWithEscapedString_Expected(), $retVal);
	}

	protected function GivenFunctionDefinitionWithEscapedString() {
		return "function escapeMe() { echo 'I contain an \\', which must be escaped.'; }";
	}

	protected function GivenFunctionDefinitionWithEscapedString_Expected() {
		return "function escapeMe() {
    echo 'I contain an \\', which must be escaped.';
}";
	}

	public function test_GivenFunctionContainingAnArray_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenFunctionContainingAnArray();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenFunctionContainingAnArray_Expected(), $retVal);
	}

	protected function GivenFunctionContainingAnArray() {
		return "function funcWithAnArray() { return [ 'arraykey' => 'arrayvalue' ]; }";
	}

	protected function GivenFunctionContainingAnArray_Expected() {
		return "function funcWithAnArray() {
    return [
        'arraykey' => 'arrayvalue'
    ];
}";
	}

	public function test_GivenAnnotationWithVariableFollowUp_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenAnnotationWithVariableFollowUp();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenAnnotationWithVariableFollowUp_Expected(), $retVal);
	}

	protected function GivenAnnotationWithVariableFollowUp() {
		return "/** AnAnnotation */public \$var;";
	}

	protected function GivenAnnotationWithVariableFollowUp_Expected() {
		return "/** AnAnnotation */
public \$var;";
	}

	public function test_GivenAnnotationWithVariableFollowUpTabbed_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenAnnotationWithVariableFollowUpTabbed();

		$retVal = $this->_formatter->format($givenString, 1);

		$this->assertEquals($this->GivenAnnotationWithVariableFollowUpTabbed_Expected(), $retVal);
	}

	protected function GivenAnnotationWithVariableFollowUpTabbed() {
		return "/** AnAnnotation */public \$var;";
	}

	protected function GivenAnnotationWithVariableFollowUpTabbed_Expected() {
		return "    /** AnAnnotation */
    public \$var;";
	}

	public function test_GivenMultipleAnnotationsWithVariableFollowUp_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenMultipleAnnotationsWithVariableFollowUp();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenMultipleAnnotationsWithVariableFollowUp_Expected(), $retVal);
	}

	protected function GivenMultipleAnnotationsWithVariableFollowUp() {
		return "/** AnAnnotation */public \$var;
/** SecondAnnotation */public \$var2;";
	}

	protected function GivenMultipleAnnotationsWithVariableFollowUp_Expected() {
		return "/** AnAnnotation */
public \$var;
/** SecondAnnotation */
public \$var2;";
	}

	public function test_GivenMultipleAnnotationsWithVariableFollowUpTabbed_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenMultipleAnnotationsWithVariableFollowUpTabbed();

		$retVal = $this->_formatter->format($givenString, 1);

		$this->assertEquals($this->GivenMultipleAnnotationsWithVariableFollowUpTabbed_Expected(), $retVal);
	}

	protected function GivenMultipleAnnotationsWithVariableFollowUpTabbed() {
		return "/** AnAnnotation */public \$var;
/** SecondAnnotation */public \$var2;";
	}

	protected function GivenMultipleAnnotationsWithVariableFollowUpTabbed_Expected() {
		return "    /** AnAnnotation */
    public \$var;
    /** SecondAnnotation */
    public \$var2;";
	}

	public function test_GivenAnnotationInStringContextExiting_indent_WillIndentArrayCorrectly() {
		$givenString = $this->GivenAnnotationInStringContextExiting();

		$retVal = $this->_formatter->format($givenString);

		$this->assertEquals($this->GivenAnnotationInStringContextExiting_Expected(), $retVal);
	}

	protected function GivenAnnotationInStringContextExiting() {
		return "\$aVariable = \"/** AnAnnotationStart\"; /** Real Annotation */";
	}

	protected function GivenAnnotationInStringContextExiting_Expected() {
		return "\$aVariable = \"/** AnAnnotationStart\";
/** Real Annotation */";
	}
}