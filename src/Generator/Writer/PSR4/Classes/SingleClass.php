<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use GraphQLGen\Generator\Writer\PSR4\PSR4StubFile;
use GraphQLGen\Generator\Writer\PSR4\PSR4Utils;

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
	 * @var PSR4StubFile
	 */
	protected $_stubFile;

	/**
	 * @var string[]
	 */
	protected $_dependencies;

	/**
	 * @var string[]
	 */
	protected $_variables;

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
		return PSR4Utils::joinAndStandardizeNamespaces($this->_namespace, $this->_className);
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
		$this->_namespace = PSR4Utils::joinAndStandardizeNamespaces($namespace);
	}

	/**
	 * @return string
	 */
	public abstract function getContent();

	/**
	 * @return PSR4StubFile
	 */
	public function getStubFile() {
		return $this->_stubFile;
	}

	/**
	 * @param PSR4StubFile $stubFile
	 */
	public function setStubFile(PSR4StubFile $stubFile) {
		$this->_stubFile = $stubFile;
	}

	/**
	 * @return \string[]
	 */
	public function getDependencies() {
		return $this->_dependencies;
	}

	/**
	 * @param string $dependency
	 */
	public function addDependency($dependency) {
		$this->_dependencies[] = $dependency;
	}

	/**
	 * @return \string[]
	 */
	public function getVariables() {
		return $this->_variables;
	}

	/**
	 * @param string $variable
	 */
	public function addVariable($variable) {
		$this->_variables[] = $variable;
	}
}