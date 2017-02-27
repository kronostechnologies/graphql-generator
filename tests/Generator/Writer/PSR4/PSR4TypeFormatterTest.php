<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Writer\PSR4\PSR4TypeFormatter;

class PSR4TypeFormatterTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'TypeName';
	const EXPECTED_TYPE_DEFINITION = 'TypeStore::getTypeDefinition(TypeName::class)';

	/**
	 * @var PSR4TypeFormatter
	 */
	protected $_typeFormatter;

	public function setUp() {
		$this->_typeFormatter = new PSR4TypeFormatter();
	}

	public function test_GivenTypeName_getFieldTypeDeclarationNonPrimaryType_WillReturnRightTypeDeclaration() {
		$type = new TypeUsage(self::TYPE_NAME, false, false, false);
		$typeFormatter = new PSR4TypeFormatter();

		$retVal = $typeFormatter->getFieldTypeDeclarationNonPrimaryType($type);

		$this->assertEquals(self::EXPECTED_TYPE_DEFINITION, $retVal);
	}
}