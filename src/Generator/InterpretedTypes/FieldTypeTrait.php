<?php


namespace GraphQLGen\Generator\InterpretedTypes;


use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

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