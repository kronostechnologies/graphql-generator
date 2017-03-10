<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\TypeStoreContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class TypeStoreContentTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'ATypeName';
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

	public function test_GivenOneTypeToImplement_getContent_WillContainFunctionDefinition() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_typeStoreContent->getContent();

		$this->assertContains("public static function", $retVal);
	}

	public function test_GivenNoTypeToImplement_getVariables_WillBeEmpty() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_typeStoreContent->getVariables();

		$this->assertEmpty($retVal);
	}

	public function test_GivenNoTypeToImplement_getVariables_WillContainVariable() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_typeStoreContent->getVariables();

		$this->assertContains("\$", $retVal);
	}

	protected function GivenNoTypeToImplement() {
		$this->_typeStore->method('getTypesToImplement')->willReturn([]);
	}

	protected function GivenOneTypeToImplement() {
		$objectType = new ObjectType();
		$objectType->setGeneratorType(new Type(self::TYPE_NAME, new StubFormatter(), []));

		$this->_typeStore->method('getTypesToImplement')->willReturn([
			$objectType
		]);
	}
}