<?php


namespace GraphQLGen\Generator\InterpretedTypes;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

trait FieldTypeTrait {
	/**
	 * @var TypeUsage
	 */
	protected $_fieldType;

	/**
	 * @return TypeUsage
	 */
	public function getFieldType() {
		return $this->_fieldType;
	}

	/**
	 * @param TypeUsage $fieldType
	 */
	public function setFieldType($fieldType) {
		$this->_fieldType = $fieldType;
	}
}