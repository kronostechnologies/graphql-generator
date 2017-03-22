<?php


namespace GraphQLGen\Generator\InterpretedTypes;


trait DescribableTypeTrait {
	/**
	 * @var string|null
	 */
	protected $_description;

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->_description = $description;
	}
}