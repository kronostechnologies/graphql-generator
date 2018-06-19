<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Main;


use GraphQLGen\Old\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;

class TypeDeclarationInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var FieldInterpretedType[]
	 */
	protected $_fields = [];

	/**
	 * @var \string[]
	 */
	protected $_interfacesNames = [];

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

	/**
	 * @return \string[]
	 */
	public function getInterfacesNames() {
		return $this->_interfacesNames;
	}

	/**
	 * @param \string[] $interfacesNames
	 */
	public function setInterfacesNames(Array $interfacesNames) {
		$this->_interfacesNames = $interfacesNames;
	}
}