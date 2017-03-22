<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\Formatters\StubFormatter;

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