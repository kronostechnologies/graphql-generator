<?php


namespace GraphQLGen\Generator\FragmentGenerators\Main;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\FragmentGenerators\FragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\MainInterpretedType;

abstract class MainFragmentGenerator extends FragmentGenerator {
	/**
	 * @var MainInterpretedType
	 */
	protected $_type;

	/**
	 * @var StubFormatter
	 */
	protected $_stubFormatter;

	/**
	 * @return MainInterpretedType
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @param MainInterpretedType $type
	 */
	public function setType($type) {
		$this->_type = $type;
	}

	/**
	 * @return StubFormatter
	 */
	public function getStubFormatter() {
		return $this->_stubFormatter;
	}

	/**
	 * @param StubFormatter $stubFormatter
	 */
	public function setStubFormatter($stubFormatter) {
		$this->_stubFormatter = $stubFormatter;
	}

	public function generateNameFragment() {

	}

	public function generateDescriptionFragment() {

	}

	/**
	 * @return string
	 */
	public abstract function generateTypeDefinition();

	/**
	 * @return string
	 */
	public abstract function getVariablesDeclarations();

	/**
	 * @return string[]
	 */
	public abstract function getDependencies();
}