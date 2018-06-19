<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Nested;


use GraphQLGen\Old\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\FieldTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;

class FieldInterpretedType implements FieldInterface {
	use NamedTypeTrait, DescribableTypeTrait, FieldTypeTrait;

	/**
	 * @var FieldArgumentInterpretedType[]
	 */
	protected $_arguments = [];

	/**
	 * @return FieldArgumentInterpretedType[]
	 */
	public function getArguments() {
		return $this->_arguments;
	}

	/**
	 * @param FieldArgumentInterpretedType[] $arguments
	 */
	public function setArguments(Array $arguments) {
		$this->_arguments = $arguments;
	}
}