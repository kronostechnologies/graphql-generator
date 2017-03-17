<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\Types\SubTypes\Field;
use GraphQLGen\Generator\Types\Type;

class TypeDeclarationInterpreter extends MainTypeInterpreter {
	/**
	 * @param ObjectTypeDefinitionNode $astNode
	 */
	public function __construct(ObjectTypeDefinitionNode $astNode) {
		$this->_astNode = $astNode;
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		return $this->_astNode->name->value;
	}

	/**
	 * @return string|null
	 */
	public function interpretDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @return Field[]
	 */
	public function interpretFields() {
		return array_map(function ($fieldNode) {
			$fieldInterpreter = new FieldInterpreter($fieldNode);

			return $fieldInterpreter->generateType();
		}, $this->_astNode->fields ?: []);
	}

	/**
	 * @return string[]
	 */
	public function interpretInterfacesNames() {
	    return array_map(function (NamedTypeNode $interfaceNameNode) {
            return $interfaceNameNode->name->value;
        }, $this->_astNode->interfaces);
    }

	/**
	 * @param \GraphQLGen\Generator\Formatters\StubFormatter $formatter
	 * @return Type
	 */
	public function generateType($formatter) {
		return new Type(
			$this->interpretName(),
			$formatter,
			$this->interpretFields(),
            $this->interpretInterfacesNames(),
			$this->interpretDescription()
		);
	}
}