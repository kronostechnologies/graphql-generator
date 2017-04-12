<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ResolverContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContentTest extends \PHPUnit_Framework_TestCase {
	const SCALAR_NAME = 'AScalarType';
	const ENUM_NAME = 'AnEnum';
	const INTERFACE_NAME = 'AnInterface';
	const TYPE_NAME = 'AType';
	const FIELD_NAME = 'AFieldName';
	const FIELD_NAME_TYPE = 'AFieldNameType';
	const FIELD_PRIMARY_TYPE = 'ID';
	const FIELD_NON_PRIMARY_TYPE = 'ANonPrimaryType';
	const CLASS_NAME = 'Class123';
	const CLASS_NS = 'A\\Namespace';
	/**
	 * @var ResolverContent
	 */
	protected $_resolverContent;

	public function setUp() {
		$this->_resolverContent = new ResolverContent();
	}

	public function test_GivenScalarGeneratorType_getContent_WontContainResolve() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenTypeNoFieldsGeneratorType_getContent_WontContainResolve() {
		$this->GivenTypeNoFieldsGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenTypeWithFieldsGeneratorType_getContent_WillContainResolve() {
		$this->GivenTypeWithFieldsGeneratorType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertContains('resolve', $retVal);
	}

	public function test_GivenPrimaryType_getContent_WontContainResolve() {
		$this->GivenPrimaryType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertNotContains('resolve', $retVal);
	}

	public function test_GivenNonPrimaryType_getContent_WillContainResolve() {
		$this->GivenNonPrimaryType();

		$retVal = $this->_resolverContent->getContent();

		$this->assertContains('resolve', $retVal);
	}

	public function test_GivenInterfaceGenerator_getContent_WillAlwaysContainResolveType() {
		$this->GivenInterfaceFragmentGenerator();

		$retVal = $this->_resolverContent->getContent();

		$this->assertContains('resolveType', $retVal);
	}


	public function test_GivenNothing_getVariables_WillAlwaysBeEmpty() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getVariables();

		$this->assertEmpty($retVal);
	}

	public function test_GivenScalarGeneratorType_getTypeGeneratorClass_WillReturnRightType() {
		$this->GivenScalarGeneratorType();

		$retVal = $this->_resolverContent->getTypeGeneratorClass();

		$this->assertEquals(ScalarFragmentGenerator::class, $retVal);
	}

	public function test_GivenSetResolverClass_getResolverClass_WillReturnResolverClass() {
		$resolverClass = $this->GivenResolverClass();
		$this->_resolverContent->setResolverClass($resolverClass);

		$retVal = $this->_resolverContent->getResolverClass();

		$this->assertEquals($resolverClass, $retVal);
	}

	public function test_GivenNothing_getParentClassName_WillBeEmpty() {
		$retVal = $this->_resolverContent->getParentClassName();

		$this->assertEmpty($retVal);
	}

	public function test_GivenResolverClass_getTypeGeneratorClass_WillCrash() {
		$resolverClass = $this->GivenResolverClass();
		$this->_resolverContent->setResolverClass($resolverClass);

		$this->expectException(Exception::class);

		$this->_resolverContent->getTypeGeneratorClass();
	}

	public function test_GivenResolverClassMock_getClassName_WillProxyThroughResolverClass() {
		$resolverClass = $this->GivenResolverClassMock();
		$this->_resolverContent->setResolverClass($resolverClass);

		$resolverClass->expects($this->once())->method('getClassName');

		$this->_resolverContent->getClassName();
	}

	public function test_GivenResolverClassMock_getNamespace_WillProxyThroughResolverClass() {
		$resolverClass = $this->GivenResolverClassMock();
		$this->_resolverContent->setResolverClass($resolverClass);

		$resolverClass->expects($this->once())->method('getNamespace');

		$this->_resolverContent->getNamespace();
	}

	protected function GivenScalarGeneratorType() {
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_NAME);

		$scalarTypeFragment = new ScalarFragmentGenerator();
		$scalarTypeFragment->setScalarType($scalarType);

		$this->_resolverContent->setFragmentGenerator($scalarTypeFragment);
	}

	protected function GivenTypeNoFieldsGeneratorType() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_resolverContent->setFragmentGenerator($objectTypeFragment);
	}

	protected function GivenTypeWithFieldsGeneratorType() {
		$fieldType1 = new TypeUsageInterpretedType();
		$fieldType1->setTypeName(self::FIELD_NAME_TYPE);

		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME);
		$field1->setFieldType($fieldType1);

		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);
		$objectType->setFields([$field1]);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_resolverContent->setFragmentGenerator($objectTypeFragment);
	}

	private function GivenResolverClass() {
		return new Resolver();
	}

	private function GivenResolverClassMock() {
		return $this->createMock(Resolver::class);
	}

	private function GivenPrimaryType() {
		$fieldType1 = new TypeUsageInterpretedType();
		$fieldType1->setTypeName(self::FIELD_PRIMARY_TYPE);

		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME);
		$field1->setFieldType($fieldType1);

		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);
		$objectType->setFields([$field1]);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_resolverContent->setFragmentGenerator($objectTypeFragment);
	}

	private function GivenNonPrimaryType() {
		$fieldType1 = new TypeUsageInterpretedType();
		$fieldType1->setTypeName(self::FIELD_NON_PRIMARY_TYPE);

		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME);
		$field1->setFieldType($fieldType1);

		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);
		$objectType->setFields([$field1]);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_resolverContent->setFragmentGenerator($objectTypeFragment);
	}

	private function GivenInterfaceFragmentGenerator() {
		$fieldType1 = new TypeUsageInterpretedType();
		$fieldType1->setTypeName(self::FIELD_NON_PRIMARY_TYPE);

		$field1 = new FieldInterpretedType();
		$field1->setName(self::FIELD_NAME);
		$field1->setFieldType($fieldType1);

		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::TYPE_NAME);
		$interfaceType->setFields([$field1]);

		$interfaceTypeFragment = new InterfaceFragmentGenerator();
		$interfaceTypeFragment->setInterfaceType($interfaceType);

		$this->_resolverContent->setFragmentGenerator($interfaceTypeFragment);
	}
}