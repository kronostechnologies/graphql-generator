<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\TypeStoreContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class TypeStoreContentTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var TypeStoreContent
	 */
	protected $_typeStoreContent;

	/**
	 * @var TypeStore|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_typeStore;

	public function setUp() {
		$this->_typeStore = $this->createMock(TypeStore::class);

		$this->_typeStoreContent = new TypeStoreContent();
		$this->_typeStoreContent->setTypeStoreClass($this->_typeStore);
	}

	public function test_GivenNoTypeToImplement_getContent_WillBeEmpty() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_typeStoreContent->getContent();

		$this->assertEmpty($retVal);
	}

	protected function GivenNoTypeToImplement() {
		$this->_typeStore->method('getTypesToImplement')->willReturn([]);
	}
}