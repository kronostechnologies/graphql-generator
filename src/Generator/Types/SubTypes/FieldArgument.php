<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class FieldArgument {
	/**
	 * @var mixed
	 */
	public $defaultValue;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var TypeUsage
	 */
	public $type;

	public function __construct($description, $name, $type, $defaultValue) {
		$this->description = $description;
		$this->name = $name;
		$this->type = $type;
		$this->defaultValue = $defaultValue;
	}
}