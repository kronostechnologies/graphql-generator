<?php


namespace GraphQLGen\Tests\Generator\Formatters;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;

class StubFormatterTest extends \PHPUnit_Framework_TestCase {
	const DUMMY_TYPE_NAME = 'AType';
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

	public function test_GivenTypeDeclarationInterpretedType_canInterpretedTypeSkipResolver_ReturnsTrue() {
		$given = new TypeDeclarationInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertFalse($retVal);
	}

	public function test_GivenInputInterpretedType_canInterpretedTypeSkipResolver_ReturnsTrue() {
		$given = new InputInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertFalse($retVal);
	}

	public function test_GivenInterfaceDeclarationInterpretedType_canInterpretedTypeSkipResolver_ReturnsTrue() {
		$given = new InterfaceDeclarationInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertFalse($retVal);
	}

	public function test_GivenUnionInterpretedType_canInterpretedTypeSkipResolver_ReturnsTrue() {
		$given = new UnionInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertFalse($retVal);
	}

	public function test_GivenScalarInterpretedType_canInterpretedTypeSkipResolver_ReturnsFalse() {
		$given = new ScalarInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertTrue($retVal);
	}

	public function test_GivenEnumInterpretedType_canInterpretedTypeSkipResolver_ReturnsFalse() {
		$given = new EnumInterpretedType();
		$this->givenDummyTypeAddedToStore($given);

		$retVal = $this->_formatter->canInterpretedTypeSkipResolver(self::DUMMY_TYPE_NAME);

		$this->assertTrue($retVal);
	}

	/**
	 * @param NamedTypeTrait $type
	 */
	protected function givenDummyTypeAddedToStore(&$type) {
		$type->setName(self::DUMMY_TYPE_NAME);
		$this->_formatter->getInterpretedTypeStore()->registerInterpretedType($type);
	}
}