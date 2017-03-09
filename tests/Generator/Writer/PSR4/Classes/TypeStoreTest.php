<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class TypeStoreTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'ScalarName';
	/**
	 * @var TypeStore
	 */
	protected $_typeStore;

	public function setUp() {
		$this->_typeStore = new TypeStore();
	}

	public function test_GivenNothing_getStubFileName_WillReturnCorrectly() {
		$retVal = $this->_typeStore->getStubFileName();

		$this->assertEquals(TypeStore::STUB_FILE, $retVal);
	}

	public function test_GivenNothing_getContentCreator_WillReturnCorrectly() {
		$retVal = $this->_typeStore->getContentCreator();

		$this->assertInstanceOf(BaseContentCreator::class, $retVal);
	}


}