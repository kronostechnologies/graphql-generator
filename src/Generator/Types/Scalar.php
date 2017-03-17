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
		$this->_formatter = $formatter;
		$this->_name = $name;
		$this->_description = $description;
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
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
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