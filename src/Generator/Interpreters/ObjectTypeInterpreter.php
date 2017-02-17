<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\GeneratorTypeInterface;
use GraphQLGen\Generator\Types\ObjectType;
use GraphQLGen\Generator\Types\ObjectTypeField;

class ObjectTypeInterpreter implements GeneratorInterpreterInterface {
    /**
     * @var ObjectTypeDefinitionNode
     */
    protected $_astNode;

    /**
     * @param ObjectTypeDefinitionNode $astNode
     */
    public function __construct($astNode) {
        $this->_astNode = $astNode;
    }

    /**
     * @param StubFormatter $formatter
     * @return GeneratorTypeInterface
     */
    public function getGeneratorType($formatter)
    {
        $fields = [];
        foreach ($this->_astNode->fields as $field) {
            $fieldTypeInterpreter = new FieldTypeInterpreter($field->type);

            $newField = new ObjectTypeField();
            $newField->name = $field->name->value;
            $newField->description = $field->description;
            $newField->fieldType = $fieldTypeInterpreter->getFieldType();

            $fields[] = $newField;
        }

        return new ObjectType(
            $this->getName(),
            $formatter,
            $fields,
            $this->getDescription()
        );
        // TODO: Implement getGeneratorType() method.
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->_astNode->name->value;
    }

    /**
     * @return string|null
     */
    public function getDescription() {
        return $this->_astNode->description;
    }
}