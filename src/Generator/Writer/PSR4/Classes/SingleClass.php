<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


abstract class SingleClass {
	/**
	 * @var string
	 */
	protected $_namespace;

	/**
	 * @var string
	 */
	protected $_className;

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->_className;
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->_namespace;
	}

	/**
	 * @return string
	 */
	public function getFullQualifiedName() {
		// ToDo: Concat namespace and className
		return "";
	}

	/**
	 * @param string $className
	 */
	public function setClassName($className) {
		$this->_className = $className;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace($namespace) {
		// ToDo: Format namespace here
		$this->_namespace = $namespace;
	}

	/**
	 * @return string
	 */
	public abstract function getContent();
}