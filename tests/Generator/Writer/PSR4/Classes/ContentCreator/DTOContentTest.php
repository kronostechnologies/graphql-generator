<?php


namespace GraphQLGen\Tests\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\DTOContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\DTO;

class DTOContentTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'AType';

	const FIELD_1_NAME = 'firstField';
	const FIELD_1_DESCRIPTION = 'This is a description of the first field';
	const FIELD_1_TYPE = 'Int';
	const FIELD_1_TYPE_TRANSLATED = 'int';

	const FIELD_2_NAME = 'secondField';
	const FIELD_2_DESCRIPTION = 'This is a description of the second field';
	const FIELD_2_TYPE = 'CustomType';
	const FIELD_2_TYPE_TRANSLATED = 'CustomType' . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX;

	const CLASS_NAME = 'DTOAType';
	const CLASS_NS = 'MyNS/DTO';
	/**
	 * @var DTO|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_dtoClass;

	/**
	 * @var DTOContent
	 */
	protected $_dtoContent;

	public function setUp() {
		$this->_dtoClass = $this->createMock(DTO::class);

		$this->_dtoContent = new DTOContent();
	}

	public function test_GivenTypeGeneratorWithNoFields_getVariables_WillBeEmpty() {
		$this->GivenTypeGeneratorWithNoFields();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertEmpty($retVal);
	}

	public function test_GivenTypeGeneratorWithOneField_getVariables_WillContainDocComment() {
		$this->GivenTypeGeneratorWithOneField();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertContains("/**", $retVal);
	}

	public function test_GivenTypeGeneratorWithOneField_getVariables_WillContainVariableDeclaration() {
		$this->GivenTypeGeneratorWithOneField();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertContains("public \$", $retVal);
	}

	public function test_GivenTypeGeneratorWithOneField_getVariables_WillContainFieldName() {
		$this->GivenTypeGeneratorWithOneField();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertContains(self::FIELD_1_NAME, $retVal);
	}

	public function test_GivenTypeGeneratorWithOneField_getVariables_WillContainTranslatedType() {
		$this->GivenTypeGeneratorWithOneField();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertContains("@type " . self::FIELD_1_TYPE_TRANSLATED, $retVal);
	}

	public function test_GivenTypeGeneratorWithOneNonPrimaryField_getVariables_WillContainType() {
		$this->GivenTypeGeneratorWithOneNonPrimaryField();

		$retVal = $this->_dtoContent->getVariables();

		$this->assertContains("@type " . self::FIELD_2_TYPE_TRANSLATED, $retVal);
	}

	public function test_GivenTypeGeneratorWithNoFields_getContent_WillAlwaysBeEmpty() {
		$this->GivenTypeGeneratorWithNoFields();

		$retVal = $this->_dtoContent->getContent();

		$this->assertEmpty($retVal);
	}

	public function test_GivenClassMockAndName_getClassName_WillBeCorrect() {
		$this->GivenClassMock();
		$this->GivenClassName();

		$retVal = $this->_dtoContent->getClassName();

		$this->assertEquals(self::CLASS_NAME, $retVal);
	}

	public function test_GivenNothing_getParentClassName_WillAlwaysBeEmpty() {
		$retVal = $this->_dtoContent->getParentClassName();

		$this->assertEmpty($retVal);
	}

	public function test_GivenClassMockAndNamespace_getNamespace_WillBeCorrect() {
		$this->GivenClassMock();
		$this->GivenNamespace();

		$retVal = $this->_dtoContent->getNamespace();

		$this->assertEquals(self::CLASS_NS, $retVal);
	}

	protected function GivenTypeGeneratorWithNoFields() {
		$typeGenerator = new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[],
			[]
		);

		$this->_dtoContent->setTypeGenerator($typeGenerator);
	}

	protected function GivenTypeGeneratorWithOneField() {
		$field1 = new Field(
			self::FIELD_1_NAME,
			self::FIELD_1_DESCRIPTION,
			new TypeUsage(
				self::FIELD_1_TYPE,
				false,
				false,
				false
			),
			[]
		);

		$typeGenerator = new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[$field1],
			[]
		);

		$this->_dtoContent->setTypeGenerator($typeGenerator);
	}

	protected function GivenTypeGeneratorWithOneNonPrimaryField() {
		$field1 = new Field(
			self::FIELD_2_NAME,
			self::FIELD_2_DESCRIPTION,
			new TypeUsage(
				self::FIELD_2_TYPE,
				false,
				false,
				false
			),
			[]
		);

		$typeGenerator = new Type(
			self::TYPE_NAME,
			new StubFormatter(),
			[$field1],
			[]
		);

		$this->_dtoContent->setTypeGenerator($typeGenerator);
	}

	protected function GivenClassMock() {
		$this->_dtoContent->setDTOClass($this->_dtoClass);
	}

	protected function GivenClassName() {
		$this->_dtoClass->method('getClassName')->willReturn(self::CLASS_NAME);
	}

	protected function GivenNamespace() {
		$this->_dtoClass->method('getNamespace')->willReturn(self::CLASS_NS);
	}
}