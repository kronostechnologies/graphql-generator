<?php


namespace GraphQLGen\Generator\InterpretedTypes\Nested;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\FieldTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;

class FieldInterpretedType {
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