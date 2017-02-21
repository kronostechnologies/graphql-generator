<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;

class ScalarType implements BaseTypeGeneratorInterface {
	/**
	 * @var null|string
	 */
	public $description;

	/**
	 * @var StubFormatter
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
	public function __construct($name, $formatter, $description = null) {
		$this->formatter = $formatter;
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$escapedName = addslashes($this->name);

		return "[ 'name' => '{$escapedName}',{$this->formatter->getDescriptionValue($this->description)} 'serialize' => [__CLASS__, 'serialize'], 'parseValue' => [__CLASS__, 'parseValue'], 'parseLiteral' => [__CLASS__, 'parseLiteral']];";
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name . 'ScalarType';
	}

	/**
	 * @return string|null
	 */
	public function getConstantsDeclaration() {
		return null;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}
}