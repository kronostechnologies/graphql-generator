<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\PSR4Resolver;

class PSR4ResolverTest extends \PHPUnit_Framework_TestCase {
	const NAMESPACE_PART_1 = '\TestNS';
	const NAMESPACE_PART_2 = 'Pt2';
	const NAMESPACE_PARTS_COMPLETE = 'TestNS\Pt2';
	const TOKEN_NAME = "TestToken";
	const TOKEN_VALUE = "NS/\"TestToken\";";
	const VALID_NAMESPACE = "Valid\\Namespace";
	const VALID_NAMESPACE_DIR = "Valid/Namespace";
	const TYPE_NAME = "TypeName";
	const TYPE_NAME_TOKEN = "NS/\"TypeName\";";
	const TYPE_NAME_SCALAR_FQN = "Types\\Scalars\\TypeName";

	/**
	 * @var PSR4Resolver
	 */
	protected $_resolver;

	public function setUp() {
		$this->_resolver = new PSR4Resolver("");
	}

	public function test_GivenNamespaceParts_joinAndStandardizeNamespaces_WillRemoveTrailingSlashesCorrectly() {
		$nsPart1 = self::NAMESPACE_PART_1;
		$nsPart2 = self::NAMESPACE_PART_2;

		$retVal = $this->_resolver->joinAndStandardizeNamespaces($nsPart1, $nsPart2);

		$this->assertStringStartsNotWith("\\", $retVal);
	}

	public function test_GivenNamespaceParts_joinAndStandardizeNamespaces_WillSeparatePartsCorrectly() {
		$nsPart1 = self::NAMESPACE_PART_1;
		$nsPart2 = self::NAMESPACE_PART_2;

		$retVal = $this->_resolver->joinAndStandardizeNamespaces($nsPart1, $nsPart2);

		$this->assertEquals(self::NAMESPACE_PARTS_COMPLETE, $retVal);
	}

	public function test_GivenDependencyName_getDependencyNamespaceToken_WillReturnCorrectToken() {
		$dependencyName = $this->GivenDependencyName();

		$retVal = $this->_resolver->getDependencyNamespaceToken($dependencyName);

		$this->assertEquals(self::TOKEN_VALUE, $retVal);
	}

	public function test_GivenType_getNamespaceForType_WillContainTypes() {
		$type = $this->GivenType();

		$retVal = $this->_resolver->getNamespaceForType($type);

		$this->assertContains("Types", $retVal);
	}

	public function test_GivenScalar_getNamespaceForType_WillContainScalars() {
		$scalar = $this->GivenScalar();

		$retVal = $this->_resolver->getNamespaceForType($scalar);

		$this->assertContains("Scalars", $retVal);
	}

	public function test_GivenEnum_getNamespaceForType_WillContainEnums() {
		$enum = $this->GivenEnum();

		$retVal = $this->_resolver->getNamespaceForType($enum);

		$this->assertContains("Enums", $retVal);
	}

	public function test_GivenInterface_getNamespaceForType_WillContainInterfaces() {
		$interface = $this->GivenInterface();

		$retVal = $this->_resolver->getNamespaceForType($interface);

		$this->assertContains("Interfaces", $retVal);
	}

	public function test_GivenType_getStubFilenameForType_WillContainObject() {
		$type = $this->GivenType();

		$retVal = $this->_resolver->getStubFilenameForType($type);

		$this->assertContains("object.stub", $retVal);
	}

	public function test_GivenScalar_getStubFilenameForType_WillContainScalar() {
		$scalar = $this->GivenScalar();

		$retVal = $this->_resolver->getStubFilenameForType($scalar);

		$this->assertContains("scalar.stub", $retVal);
	}

	public function test_GivenEnum_getStubFilenameForType_WillContainEnum() {
		$enum = $this->GivenEnum();

		$retVal = $this->_resolver->getStubFilenameForType($enum);

		$this->assertContains("enum.stub", $retVal);
	}

	public function test_GivenInterface_getStubFilenameForType_WillContainInterface() {
		$interface = $this->GivenInterface();

		$retVal = $this->_resolver->getStubFilenameForType($interface);

		$this->assertContains("interface.stub", $retVal);
	}

	public function test_GivenScalar_storeFQNTokenForType_WillHaveAddedToken() {
		$scalar = $this->GivenScalar();

		$this->_resolver->storeFQNTokenForType($scalar);
		$retVal = $this->_resolver->getAllResolvedTokens();

		$this->assertArrayHasKey(self::TYPE_NAME_TOKEN, $retVal);
	}

	public function test_GivenScalar_getFQNForType_WillReturnRightFQN() {
		$scalar = $this->GivenScalar();

		$retVal = $this->_resolver->getFQNForType($scalar);

		$this->assertEquals(self::TYPE_NAME_SCALAR_FQN, $retVal);
	}

	protected function GivenDependencyName() {
		return self::TOKEN_NAME;
	}

	protected function GivenType() {
		return new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[]
		);
	}

	protected function GivenScalar() {
		return new Scalar(
			self::TYPE_NAME,
			new StubFormatter()
		);
	}

	protected function GivenEnum() {
		return new Enum(
			self::TYPE_NAME,
			[],
			new StubFormatter()
		);
	}

	protected function GivenInterface() {
		return new InterfaceDeclaration(
			self::TYPE_NAME,
			[],
			new StubFormatter()
		);
	}

	protected function GivenNamespace() {
		return self::VALID_NAMESPACE;
	}

}