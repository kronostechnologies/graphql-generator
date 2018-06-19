<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Nested;


interface FieldInterface {
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * @return TypeUsageInterpretedType
	 */
	public function getFieldType();

	/**
	 * @param TypeUsageInterpretedType $type
	 */
	public function setFieldType($type);
}