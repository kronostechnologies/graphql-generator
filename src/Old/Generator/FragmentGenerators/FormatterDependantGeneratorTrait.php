<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;

trait FormatterDependantGeneratorTrait {
	/**
	 * @var StubFormatter
	 */
	protected $_formatter;

	/**
	 * @return StubFormatter
	 */
	public function getFormatter() {
		return $this->_formatter;
	}

	/**
	 * @param StubFormatter $formatter
	 */
	public function setFormatter($formatter) {
		$this->_formatter = $formatter;
	}
}