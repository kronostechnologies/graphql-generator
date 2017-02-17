<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\EnumType;
use GraphQLGen\Generator\Types\EnumTypeValue;

class EnumInterpreter implements GeneratorInterpreterInterface {

	/**
	 * @var EnumTypeDefinitionNode
	 */
	protected $_astNode;

	/**
	 * @param EnumTypeDefinitionNode $astNode
	 */
	public function __construct($astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @param StubFormatter $formatter
	 * @return EnumType
	 */
	public function getGeneratorType($formatter) {
		return new EnumType(
			$this->getName(),
			$this->getEnumValues(),
			$formatter,
			$this->getDescription()
		);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return EnumTypeValue[]
	 */
	public function getEnumValues() {
		$enumValues = [];

		foreach($this->_astNode->values as $possibleValue) {
			$enumValues[] = new EnumTypeValue(
				$possibleValue->name->value,
				$possibleValue->description
			);
		}

		return $enumValues;
	}

	/**
	 * @return string|null
	 */
	public function getDescription() {
		return $this->_astNode->description;
	}
}