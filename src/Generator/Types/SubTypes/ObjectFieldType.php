<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class ObjectFieldType {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var FieldType
	 */
	public $fieldType;

	/**
	 * @var ObjectFieldTypeArgument[]
	 */
	public $arguments = [];
}