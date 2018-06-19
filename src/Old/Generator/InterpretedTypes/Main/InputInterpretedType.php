<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Main;


use GraphQLGen\Old\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\InputFieldInterpretedType;

class InputInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var InputFieldInterpretedType[]
	 */
	protected $_fields = [];

	/**
	 * @return InputFieldInterpretedType[]
	 */
	public function getFields() {
		return $this->_fields;
	}

	/**
	 * @param InputFieldInterpretedType[] $fields
	 */
	public function setFields(Array $fields) {
		$this->_fields = $fields;
	}
}