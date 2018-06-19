<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Main;


use GraphQLGen\Old\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\EnumValueInterpretedType;

class EnumInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var EnumValueInterpretedType[]
	 */
	protected $_values = [];

	/**
	 * @return EnumValueInterpretedType[]
	 */
	public function getValues() {
		return $this->_values;
	}

	/**
	 * @param EnumValueInterpretedType[] $values
	 */
	public function setValues(Array $values) {
		$this->_values = $values;
	}
}