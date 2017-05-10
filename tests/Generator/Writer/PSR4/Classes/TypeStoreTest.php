<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Generator\Writer\Namespaced\Classes\TypeStore;

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

	public function test_GivenNewTypeImplementation_getTypesToImplement_WillContainType() {
		$givenTypeImplementation = $this->GivenTypeImplementation();
		$this->_typeStore->addTypeImplementation($givenTypeImplementation);

		$retVal = $this->_typeStore->getTypesToImplement();

		$this->assertContains($givenTypeImplementation, $retVal);
	}

	protected function GivenTypeImplementation() {
		return new ObjectType();
	}


}