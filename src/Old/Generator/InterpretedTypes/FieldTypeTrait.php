<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes;


use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

trait FieldTypeTrait {
	/**
	 * @var TypeUsageInterpretedType
	 */
	protected $_fieldType;

	/**
	 * @return TypeUsageInterpretedType
	 */
	public function getFieldType() {
		return $this->_fieldType;
	}

	/**
	 * @param TypeUsageInterpretedType $fieldType
	 */
	public function setFieldType($fieldType) {
		$this->_fieldType = $fieldType;
	}
}