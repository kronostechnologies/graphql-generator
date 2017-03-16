<?php


namespace GraphQLGen\Generator\Types;


abstract class BaseTypeGenerator {
	/**
	 * @var \GraphQLGen\Generator\Formatters\StubFormatter
	 */
	public $formatter;

	/**
	 * @return string
	 */
	public abstract function generateTypeDefinition();

	/**
	 * @return string
	 */
	public abstract function getName();

	/**
	 * @return string|null
	 */
	public abstract function getVariablesDeclarations();

	/**
	 * @return string[]
	 */
	public abstract function getDependencies();

	/**
	 * @param string $description
	 * @return string
	 */
	protected function getDescriptionFragment($description) {
		if (empty($description)) {
			return "";
		}
		else {
			return "'description' => '" . $this->formatter->standardizeDescription($description) . "'";
		}
	}
}