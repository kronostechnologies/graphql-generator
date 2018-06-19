<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\ClassComposer;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\ResolverFactoryContent;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ResolverFactory;

class ResolverFactoryContentTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'AType';

	/**
	 * @var ResolverFactoryContent
	 */
	protected $_resolverFactoryContent;

	/**
	 * @var ResolverFactory|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_resolverFactory;

	public function setUp() {
		$this->_resolverFactory = $this->createMock(ResolverFactory::class);

		$this->_resolverFactoryContent = new ResolverFactoryContent();
		$this->_resolverFactoryContent->setResolverFactoryClass($this->_resolverFactory);
	}

	public function test_GivenOneTypeToImplement_getContent_WillContainFunctionDefinition() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_resolverFactoryContent->getContent();

		$this->assertContains('public function', $retVal);
	}

	public function test_GivenNoTypeToImplement_getContent_WontContainFunctionDefinition() {
		$this->GivenNoTypeToImplement();

		$retVal = $this->_resolverFactoryContent->getContent();

		$this->assertNotContains('public function', $retVal);
	}

	public function test_GivenOneTypeToImplement_getContent_WillContainResolverInstanciation() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_resolverFactoryContent->getContent();

		$this->assertContains('new ', $retVal);
	}

	public function test_GivenOneTypeToImplement_getClassName_WillProxyThroughClass() {
		$this->_resolverFactory->expects($this->once())->method('getClassName');

		$this->_resolverFactoryContent->getClassName();
	}

	public function test_GivenOneTypeToImplement_getVariables_IsEmpty() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_resolverFactoryContent->getVariables();

		$this->assertEmpty($retVal);
	}

	public function test_GivenOneTypeToImplement_getParentClassName_IsEmpty() {
		$this->GivenOneTypeToImplement();

		$retVal = $this->_resolverFactoryContent->getParentClassName();

		$this->assertEmpty($retVal);
	}

	protected function GivenNoTypeToImplement() {
		$this->_resolverFactory->method('getTypeResolversToAdd')->willReturn([]);
	}

	protected function GivenOneTypeToImplement() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_resolverFactory->method('getTypeResolversToAdd')->willReturn([
			$objectTypeFragment
		]);
	}
}