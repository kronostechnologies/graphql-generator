<?php


namespace GraphQLGen\Generator\InterpretedTypes\Main;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\EnumValue;

class EnumInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var EnumValue[]
	 */
	protected $_values = [];

	/**
	 * @return EnumValue[]
	 */
	public function getValues() {
		return $this->_values;
	}

	/**
	 * @param EnumValue[] $values
	 */
	public function setValues($values) {
		$this->_values = $values;
	}
}