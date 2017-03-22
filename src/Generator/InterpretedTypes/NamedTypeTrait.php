<?php


namespace GraphQLGen\Generator\InterpretedTypes;


trait NamedTypeTrait {
	/**
	 * @var string
	 */
	protected $_name = "";

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->_name = $name;
	}

}