<?php


namespace GraphQLGen\Tests\Generator\Formatters;


use GraphQLGen\Generator\Formatters\AnnotationFormatter;

class AnnotationFormatterTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = "AType";
	/**
	 * @var AnnotationFormatter
	 */
	protected $_annotationFormatter;

	public function setUp() {
		$this->_annotationFormatter = new AnnotationFormatter();
	}

	public function test_GivenNullableType_getAnnotationType_WillContainNull() {
		$retVal = $this->_annotationFormatter->getAnnotationType(self::TYPE_NAME, true);

		$this->assertContains("|null", $retVal);
	}

	public function test_GivenNonNullableType_getAnnotationType_WontContainNull() {
		$retVal = $this->_annotationFormatter->getAnnotationType(self::TYPE_NAME, false);

		$this->assertNotContains("|null", $retVal);
	}

	public function test_GivenNullableType_getAnnotationString_WillBeAComment() {
		$retVal = $this->_annotationFormatter->getAnnotationString(self::TYPE_NAME, true);

		$this->assertStringStartsWith("/**", $retVal);
	}
}