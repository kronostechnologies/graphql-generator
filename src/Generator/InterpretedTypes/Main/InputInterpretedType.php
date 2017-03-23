<?php


namespace GraphQLGen\Generator\InterpretedTypes\Main;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\InputField;

class InputInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var InputField[]
	 */
	protected $_fields = [];

	/**
	 * @return InputField[]
	 */
	public function getFields() {
		return $this->_fields;
	}

	/**
	 * @param InputField[] $fields
	 */
	public function setFields(Array $fields) {
		$this->_fields = $fields;
	}
}