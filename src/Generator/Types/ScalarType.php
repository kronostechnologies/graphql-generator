<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\StubFormatter;

class ScalarType implements GeneratorTypeInterface {
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
	public function GenerateTypeDefinition() {
		$escapedName = addslashes($this->name);

		return "
            [
                'name' => '{$escapedName}',{$this->formatter->getDescriptionLine($this->description, 4)}            
                'serialize' => [__CLASS__, 'serialize'],
                'parseValue' => [__CLASS__, 'parseValue'],
                'parseLiteral' => [__CLASS__, 'parseLiteral'],
            ]";
	}

	/**
	 * @return string
	 */
	public function GetStubFile() {
		return __DIR__ . '/stubs/scalar.stub';
	}

	/**
	 * @return string
	 */
	public function GetNamespacePart() {
		return "Types\\Scalar";
	}

	/**
	 * @return mixed
	 */
	public function GetClassName() {
		return $this->name . 'ScalarType';
	}

	/**
	 * @return string|null
	 */
	public function GetConstantsDeclaration() {
		return null;
	}

	/**
	 * @return string|null
	 */
	public function GetUsesDeclaration() {
		return null;
	}
}