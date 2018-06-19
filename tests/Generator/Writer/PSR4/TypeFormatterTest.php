<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;
use GraphQLGen\Old\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Old\Generator\Writer\Namespaced\WithResolverFormatter;

class TypeFormatterTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'TypeName';
	const EXPECTED_TYPE_DEFINITION = 'TypeStore::TypeName()';

	/**
	 * @var WithResolverFormatter
	 */
	protected $_typeFormatter;

	public function setUp() {
		$this->_typeFormatter = new WithResolverFormatter();
	}

	public function test_GivenTypeWithName_getFieldTypeDeclarationNonPrimaryType_WillReturnRightTypeDeclaration() {
		$type = $this->GivenTypeWithName();
		$typeFormatter = new WithResolverFormatter();

		$retVal = $typeFormatter->getFieldTypeDeclarationNonPrimaryType($type->getTypeName());

		$this->assertEquals(self::EXPECTED_TYPE_DEFINITION, $retVal);
	}

	protected function GivenTypeWithName() {
		$type = new TypeUsageInterpretedType();
		$type->setTypeName(self::TYPE_NAME);

		return $type;
	}
}