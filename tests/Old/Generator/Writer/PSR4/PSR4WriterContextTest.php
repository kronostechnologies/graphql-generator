<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\Writer\Namespaced\NamespacedWriterContext;

class PSR4WriterContextTest extends \PHPUnit_Framework_TestCase {
	const FORMATTER_USE_SPACES = true;
	const FORMATTER_TAB_SIZE = 8;
	const STUB_FILE_NAME = 'AStubFile.stub';

	/**
	 * @var NamespacedWriterContext
	 */
	protected $_writerContext;

	public function setUp() {
		$this->_writerContext = new NamespacedWriterContext();
	}

	public function test_GivenStubFileName_getStubFilePath_WillContainStubDirectory() {
		$retVal = $this->_writerContext->getStubFilePath(self::STUB_FILE_NAME);

		$this->assertContains("/stubs/", $retVal);
	}

	public function test_GivenConfiguration_getConfiguredClassFormatter_WillConfigureClassFormatterCorrectly() {
		$this->GivenConfiguration();

		$retVal = $this->_writerContext->getConfiguredClassFormatter();

		$this->assertEquals(self::FORMATTER_USE_SPACES, $retVal->usesSpaces());
		$this->assertEquals(self::FORMATTER_TAB_SIZE, $retVal->getTabSize());
	}

	protected function GivenConfiguration() {
		$this->_writerContext->formatter = new StubFormatter();
		$this->_writerContext->formatter->useSpaces = self::FORMATTER_USE_SPACES;
		$this->_writerContext->formatter->tabSize = self::FORMATTER_TAB_SIZE;
	}
}