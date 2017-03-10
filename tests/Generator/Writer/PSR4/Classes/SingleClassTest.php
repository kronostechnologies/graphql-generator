<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\PSR4\ClassStubFile;
use GraphQLGen\Tests\Mocks\SingleClassDummy;

class SingleClassTest extends \PHPUnit_Framework_TestCase {
	const CLASS_NAME = 'AClassName';
	const CORRECT_NAMESPACE = 'NS\\Domain';
	const PARENT_CLASS_NAME = 'ParentClassName';
	const NEW_DEPENDENCY = 'NewAddedDependency';
	const NEW_VARIABLE = 'NewAddedVariable';
	/**
	 * @var SingleClassDummy
	 */
	protected $_singleClassDummy;

	public function setUp() {
		$this->_singleClassDummy = new SingleClassDummy();
	}

	public function test_GivenSetClassName_getClassName_WillReturnClassName() {
		$this->_singleClassDummy->setClassName(self::CLASS_NAME);

		$retVal = $this->_singleClassDummy->getClassName();

		$this->assertEquals(self::CLASS_NAME, $retVal);
	}

	public function test_GivenSetCorrectNamespace_getNamespace_WillReturnNamespace() {
		$this->_singleClassDummy->setNamespace(self::CORRECT_NAMESPACE);

		$retVal = $this->_singleClassDummy->getNamespace();

		$this->assertEquals(self::CORRECT_NAMESPACE, $retVal);
	}

	public function test_GivenSetParentClassName_getParentClassName_WillReturnParentClassName() {
		$this->_singleClassDummy->setParentClassName(self::PARENT_CLASS_NAME);

		$retVal = $this->_singleClassDummy->getParentClassName();

		$this->assertEquals(self::PARENT_CLASS_NAME, $retVal);
	}

	public function test_GivenNamespaceAndClassName_getFullQualifiedName_WillEndWithClassName() {
		$this->_singleClassDummy->setClassName(self::CLASS_NAME);
		$this->_singleClassDummy->setNamespace(self::CORRECT_NAMESPACE);

		$retVal = $this->_singleClassDummy->getFullQualifiedName();

		$this->assertStringEndsWith(self::CLASS_NAME, $retVal);
	}

	public function test_GivenNamespaceAndClassName_getFullQualifiedName_WillContainNamespace() {
		$this->_singleClassDummy->setClassName(self::CLASS_NAME);
		$this->_singleClassDummy->setNamespace(self::CORRECT_NAMESPACE);

		$retVal = $this->_singleClassDummy->getFullQualifiedName();

		$this->assertContains(self::CORRECT_NAMESPACE, $retVal);
	}

	public function test_GivenSetStubFile_getStubFile_WillReturnStubFile() {
		$givenStubFile = $this->GivenStubFile();
		$this->_singleClassDummy->setStubFile($givenStubFile);

		$retVal = $this->_singleClassDummy->getStubFile();

		$this->assertEquals($givenStubFile, $retVal);
	}

	public function test_GivenAddedDependency_getDependencies_WillContainDependency() {
		$this->_singleClassDummy->addDependency(self::NEW_DEPENDENCY);

		$retVal = $this->_singleClassDummy->getDependencies();

		$this->assertContains(self::NEW_DEPENDENCY, $retVal);
	}

	public function test_GivenAddedVariable_getVariables_WillContainVariable() {
		$this->_singleClassDummy->addVariable(self::NEW_VARIABLE);

		$retVal = $this->_singleClassDummy->getVariables();

		$this->assertContains(self::NEW_VARIABLE, $retVal);
	}

	private function GivenStubFile() {
		return new ClassStubFile();
	}
}