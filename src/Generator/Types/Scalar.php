<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;

class Scalar extends BaseTypeGenerator {
	/**
	 * ScalarType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setDescription($description);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$nameFragment = $this->getNameFragment();
		$descriptionFragment = $this->getDescriptionFragment($this->getDescription());

		return "{$nameFragment}{$descriptionFragment}";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		return null;
	}

	/**
	 * @param string $description
	 * @return string
	 */
	protected function getDescriptionFragment($description) {
		if (empty($description)) {
			return "";
		}
		else {
			return "\$this->description = '" . $this->_formatter->standardizeDescription($description) . "';";
		}
	}

	protected function getNameFragment() {
		return "\$this->name = '{$this->getName()}';";
	}
}