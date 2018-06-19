<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4\Classes;


use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\ResolverFactoryContent;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ResolverFactory;

class ResolverFactoryTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var ResolverFactory
	 */
	protected $_resolverFactory;

	public function setUp() {
		$this->_resolverFactory = new ResolverFactory();
	}

	public function test_GivenNothing_getStubFileName_IsCorrect() {
		$retVal = $this->_resolverFactory->getStubFileName();

		$this->assertEquals(ResolverFactory::STUB_FILE, $retVal);
	}

	public function test_GivenNothing_getContentCreator_ReturnsCorrectInstanceType() {
		$retVal = $this->_resolverFactory->getContentCreator();

		$this->assertInstanceOf(ResolverFactoryContent::class, $retVal);
	}

	public function test_GivenAddedTypeResolver_getTypeResolversToAdd_WillReturnTypeResolverArray() {
		$this->GivenAddedTypeResolver();

		$retVal = $this->_resolverFactory->getTypeResolversToAdd();

		$this->assertNotEmpty($retVal);
	}

	protected function GivenAddedTypeResolver() {
		$this->_resolverFactory->addResolveableTypeImplementation(new ObjectType());
	}
}