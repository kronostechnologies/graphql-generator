<?php


namespace GraphQLGen\Tests\Old\Generator\InterpretedTypes;


use Exception;
use GraphQLGen\Old\Generator\InterpretedTypes\InterpretedTypesStore;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\UnionInterpretedType;

class InterpretedTypesStoreTest extends \PHPUnit_Framework_TestCase {
	const ENUM_DEC_NAME = 'Enumeration';
	const INPUT_DEC_NAME = 'InputVal';
	const INTERFACE_DEC_NAME = 'BaseClass';
	const SCALAR_DEC_NAME = 'DateTime';
	const TYPE_DEC_NAME = 'FirstTypeDeclaration';
	const UNION_DEC_NAME = 'AllInOne';

	/**
	 * @var InterpretedTypesStore
	 */
	protected $_interpretedTypesStore;

	public function setUp() {
		$this->_interpretedTypesStore = new InterpretedTypesStore();
	}

	public function test_GivenEmptyStore_registerInterpretedType_WillAddTypeSuccessfully() {
		$type = $this->givenTypeDeclarationInterpretedType();

		try {
			$this->_interpretedTypesStore->registerInterpretedType($type);
		} catch (\Exception $ex) {
			$this->fail($ex->getMessage());
		}
	}

	public function test_GivenTypeDeclaration_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenTypeDeclarationInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenInterfaceDeclarationInterpretedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenInterfaceDeclarationInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenUnionInterpretedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenUnionInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenInputInterpretedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenInputInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenScalarInterpretedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenScalarInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenEnumInterpretedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenEnumInterpretedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertTrue($retVal);
	}

	public function test_givenUndefinedType_isMainInterpretedType_ReturnsTrue() {
		$type = $this->givenUndefinedType();

		$retVal = $this->_interpretedTypesStore->isMainInterpretedType($type);

		$this->assertFalse($retVal);
	}

	public function test_givenOnceRegisteredType_registerInterpretedType_ThrowsException() {
		$type = $this->givenTypeDeclarationInterpretedType();

		$this->expectException(\Exception::class);
		$this->expectExceptionMessageRegExp("/already contained in the store/");

		$this->_interpretedTypesStore->registerInterpretedType($type);
		$this->_interpretedTypesStore->registerInterpretedType($type);
	}

	public function test_givenUndefinedType_registerInterpretedType_ThrowsException() {
		$type = $this->givenUndefinedType();

		$this->expectException(\Exception::class);
		$this->expectExceptionMessageRegExp("/to be of main type/");

		$this->_interpretedTypesStore->registerInterpretedType($type);
	}

	public function test_givenMultipleValidTypes_registerInterpretedType_Succeed() {
		$type1 = $this->givenInputInterpretedType();
		$type2 = $this->givenScalarInterpretedType();

		try {
			$this->_interpretedTypesStore->registerInterpretedType($type1);
			$this->_interpretedTypesStore->registerInterpretedType($type2);
		} catch (Exception $ex) {
			$this->fail($ex->getMessage());
		}
	}

	public function test_givenAddedType_getInterpretedTypeByName_ReturnsCorrectType() {
		$addedType = $this->givenInputInterpretedType();
		$this->_interpretedTypesStore->registerInterpretedType($addedType);

		$retVal = $this->_interpretedTypesStore->getInterpretedTypeByName(self::INPUT_DEC_NAME);

		$this->assertEquals($addedType, $retVal);
	}

	public function test_givenTypeNotAdded_getInterpretedTypeByName_ThrowsException() {
		$this->expectException(Exception::class);
		$this->expectExceptionMessageRegExp("/not found/");

		$this->_interpretedTypesStore->getInterpretedTypeByName(self::INPUT_DEC_NAME);
	}

	protected function givenTypeDeclarationInterpretedType() {
		$typeDeclaration = new TypeDeclarationInterpretedType();
		$typeDeclaration->setName(self::TYPE_DEC_NAME);

		return $typeDeclaration;
	}

	protected function givenInterfaceDeclarationInterpretedType() {
		$interfaceType = new InterfaceDeclarationInterpretedType();
		$interfaceType->setName(self::INTERFACE_DEC_NAME);

		return $interfaceType;
	}

	protected function givenUnionInterpretedType() {
		$unionType = new UnionInterpretedType();
		$unionType->setName(self::UNION_DEC_NAME);

		return $unionType;
	}

	protected function givenInputInterpretedType() {
		$inputType = new InputInterpretedType();
		$inputType->setName(self::INPUT_DEC_NAME);

		return $inputType;
	}

	protected function givenScalarInterpretedType() {
		$scalarType = new ScalarInterpretedType();
		$scalarType->setName(self::SCALAR_DEC_NAME);

		return $scalarType;
	}

	protected function givenEnumInterpretedType() {
		$enumType = new EnumInterpretedType();
		$enumType->setName(self::ENUM_DEC_NAME);

		return $enumType;
	}

	protected function givenUndefinedType() {
		return new \stdClass();
	}
}