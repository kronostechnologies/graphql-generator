<?php


namespace GraphQLGen\Tests\Generator\Types\SubTypes;


use GraphQLGen\Generator\Types\SubTypes\InputField;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class InputFieldTest extends \PHPUnit_Framework_TestCase {
	const INPUT_DESC = 'A Description';
	const INPUT_NAME = 'AName';

	public function test_GivenConstructorWithName_getName_WillReturnName() {
		$inputField = $this->GivenConstructorWithName();
		
		$retVal = $inputField->getName();
		
		$this->assertEquals(self::INPUT_NAME, $retVal);
	}
	
	public function test_GivenConstructorWithDescription_getDescription_WillReturnDescription() {
		$inputField = $this->GivenConstructorWithDescription();
		
		$retVal = $inputField->getDescription();
		
		$this->assertEquals(self::INPUT_DESC, $retVal);
	}
	
	public function test_GivenConstructorWithFieldType_getFieldType_WillReturnFieldType() {
		$typeUsage = $this->GivenTypeUsage();
		$inputField = $this->GivenConstructorWithFieldType($typeUsage);
		
		$retVal = $inputField->getFieldType();
		
		$this->assertEquals($typeUsage, $retVal);
	}
	
	protected function GivenConstructorWithName() {
		return new InputField(self::INPUT_NAME, null, null);
	}
	
	protected function GivenConstructorWithDescription() {
		return new InputField(null, self::INPUT_DESC, null);
	}
	
	protected function GivenConstructorWithFieldType(TypeUsage $typeUsage) {
		return new InputField(null, null, $typeUsage);
	}
	
	protected function GivenTypeUsage() {
		return new TypeUsage("", false, false, false);
	}
}