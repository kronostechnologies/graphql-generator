<?php


namespace GraphQLGen\Tests\Old\Generator\Writer\PSR4\Classes;


use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\DTOContent;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\DTO;

class DTOTest extends \PHPUnit_Framework_TestCase {
	const TYPE_NAME = 'AType';
	/**
	 * @var DTO
	 */
	protected $_dto;

	public function setUp() {
		$this->_dto = new DTO();
	}

	public function test_GivenNothing_getStubFileName_WillReturnCorrectly() {
		$retVal = $this->_dto->getStubFileName();

		$this->assertEquals(DTO::STUB_FILE, $retVal);
	}

	public function test_GivenTypeGeneratorType_getContentCreator_WillReturnCorrectly() {
		$this->GivenTypeGeneratorType();

		$retVal = $this->_dto->getContentCreator();

		$this->assertInstanceOf(DTOContent::class, $retVal);
	}

	protected function GivenTypeGeneratorType() {
		$objectType = new TypeDeclarationInterpretedType();
		$objectType->setName(self::TYPE_NAME);

		$objectTypeFragment = new TypeDeclarationFragmentGenerator();
		$objectTypeFragment->setTypeDeclaration($objectType);

		$this->_dto->setFragmentGenerator($objectTypeFragment);
	}
}