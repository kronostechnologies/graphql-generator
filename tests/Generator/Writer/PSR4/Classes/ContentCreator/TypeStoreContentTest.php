<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\TypeStoreContent;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Generator\Writer\Namespaced\Classes\TypeStore;

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

	public function test_GivenOneTypeToImplement_getContent_WillContainFunctionDefinition() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_typeStoreContent->getContent();

		$this->assertContains("public static function", $retVal);
	}

	public function test_GivenNoTypeToImplement_getVariables_WillContainVariable() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_typeStoreContent->getVariables();

		$this->assertContains("\$", $retVal);
	}

	public function test_GivenTypeStoreClassMock_getClassName_WillProxyThroughClass() {
		$this->_typeStore->expects($this->once())->method('getClassName');

		$this->_typeStoreContent->getClassName();
	}

	public function test_GivenTypeStoreClassMock_getNamespace_WillProxyThroughClass() {
		$this->_typeStore->expects($this->once())->method('getNamespace');

		$this->_typeStoreContent->getNamespace();
	}

	public function test_GivenNoTypeToImplement_getVariables_WillContainResolverFactory() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_typeStoreContent->getVariables();

		$this->assertContains(TypeStoreContent::RESOLVER_FACTORY_VAR, $retVal);
	}

	public function test_GivenNoTypeToImplement_getContent_WillContainResolverFactorySetter() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_typeStoreContent->getContent();

		$this->assertContains(TypeStoreContent::RESOLVER_FACTORY_SETTER_FUNC, $retVal);
	}

	public function test_GivenNoTypeToImplement_getContent_WillContainResolverFactoryGetter() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_typeStoreContent->getContent();

		$this->assertContains(TypeStoreContent::RESOLVER_FACTORY_GETTER_FUNC, $retVal);
	}

	public function test_GivenNothing_getParentClassName_WillBeEmpty() {
		$retVal = $this->_typeStoreContent->getParentClassName();

		$this->assertEmpty($retVal);
	}

	protected function GivenNoTypeToImplement() {
		$this->_typeStore->method('getTypesToImplement')->willReturn([]);
	}

	protected function GivenOneTypeToImplement() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$objectTypeClass = new ObjectType();
		$objectTypeClass->setFragmentGenerator($objectTypeFragment);

		$this->_typeStore->method('getTypesToImplement')->willReturn([
			$objectTypeClass
		]);
	}
}