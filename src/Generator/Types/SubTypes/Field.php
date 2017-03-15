<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class Field {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string|null
	 */
	public $description;

	/**
	 * @var TypeUsage
	 */
	public $fieldType;

	/**
	 * @var FieldArgument[]
	 */
	public $arguments = [];

	/**
	 * Field constructor.
	 * @param string $name
	 * @param string|null $description
	 * @param TypeUsage $fieldType
	 * @param FieldArgument[] $arguments
	 */
	public function __construct($name, $description, $fieldType, Array $arguments) {
		$this->name = $name;
		$this->description = $description;
		$this->fieldType = $fieldType;
		$this->arguments = $arguments;
	}
}