<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4\Classes;


use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\ResolverContent;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\Resolver;

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
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);

		$this->_resolver->setFragmentGenerator($scalarTypeFragment);
	}
}