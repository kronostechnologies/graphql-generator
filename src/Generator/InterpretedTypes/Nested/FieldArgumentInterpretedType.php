<?php


namespace GraphQLGen\Generator\InterpretedTypes\Nested;


use GraphQLGen\Generator\InterpretedTypes\FieldTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;

class FieldArgumentInterpretedType {
	use NamedTypeTrait, FieldTypeTrait;

	/**
	 * @var mixed
	 */
	protected $_defaultValue;

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->_defaultValue;
	}

	/**
	 * @param mixed $defaultValue
	 */
	public function setDefaultValue($defaultValue) {
		$this->_defaultValue = $defaultValue;
	}
}