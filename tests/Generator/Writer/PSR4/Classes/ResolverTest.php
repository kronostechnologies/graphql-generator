<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'ScalarName';
	/**
	 * @var Resolver
	 */
	protected $_resolver;

	public function setUp() {
		$this->_resolver = new Resolver();
	}

	public function test_GivenNothing_getStubFileName_WillReturnCorrectly() {
		$retVal = $this->_resolver->getStubFileName();

		$this->assertEquals(Resolver::STUB_FILE, $retVal);
	}

	public function test_GivenScalarGeneratorType_getContentCreator_WillReturnCorrectly() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolver->getContentCreator();

		$this->assertInstanceOf(ResolverContent::class, $retVal);
	}

	protected function GivenScalarGeneratorType() {
		$this->_resolver->setGeneratorType(new Scalar(
			self::SCALAR_NAME,
			new StubFormatter()
		));
	}
}