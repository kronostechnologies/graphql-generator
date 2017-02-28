<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;

class Scalar implements BaseTypeGeneratorInterface {
	/**
	 * @var null|string
	 */
	public $description;

	/**
	 * @var \GraphQLGen\Generator\Formatters\StubFormatter
	 */
	public $formatter;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * ScalarType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, $description = null) {
		$this->formatter = $formatter;
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		return "[ 'name' => '{$this->name}',{$this->formatter->getDescriptionValue($this->description)} 'serialize' => [__CLASS__, 'serialize'], 'parseValue' => [__CLASS__, 'parseValue'], 'parseLiteral' => [__CLASS__, 'parseLiteral']];";
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
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
}