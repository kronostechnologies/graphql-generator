<?php


namespace GraphQLGen\Generator\InterpretedTypes\Main;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;

class InterfaceDeclarationInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var FieldInterpretedType[]
	 */
	protected $_fields = [];

	/**
	 * @return FieldInterpretedType[]
	 */
	public function getFields() {
		return $this->_fields;
	}

	/**
	 * @param FieldInterpretedType[] $fields
	 */
	public function setFields(Array $fields) {
		$this->_fields = $fields;
	}
}