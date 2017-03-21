<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class Union extends BaseTypeGenerator {

	/**
	 * @var TypeUsage[]
	 */
	protected $_types;

	public function __construct($name, $formatter, Array $types, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setTypes($types);
		$this->setDescription($description);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		// TODO: Implement generateTypeDefinition() method.
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		// ToDo: Enumerate types
	}

	/**
	 * @return TypeUsage[]
	 */
	public function getTypes() {
		return $this->_types;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		return null;
	}

	/**
	 * @param TypeUsage[] $types
	 */
	public function setTypes($types) {
		$this->_types = $types;
	}
}