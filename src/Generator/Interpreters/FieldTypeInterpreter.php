<?php


namespace GraphQLGen\Generator\Interpreters;


use GraphQL\Language\AST\ListTypeNode;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQLGen\Generator\Types\FieldType;

class FieldTypeInterpreter {
    /**
     * @var ListTypeNode|NamedTypeNode|NonNullTypeNode
     */
    protected $_astNode;

    /**
     * FieldTypeInterpreter constructor.
     * @param NonNullTypeNode|ListTypeNode|NamedTypeNode $astNode
     */
    public function __construct($astNode) {
        $this->_astNode = $astNode;
    }

    /**
     * @return FieldType
     */
    public function getFieldType() {
        if (get_class($this->_astNode) === NonNullTypeNode::class) {
            $inList = get_class($this->_astNode->type) === ListTypeNode::class;
            $isNullableList = !$inList;
            $isNullableObject = $inList ? $this->_astNode->type->type !== NonNullTypeNode::class : false;
        }
        else {
            $inList = get_class($this->_astNode) === ListTypeNode::class;
            $isNullableList = true;
            $isNullableObject = $inList ? $this->_astNode->type === NonNullTypeNode::class : true;
        }

        // Finds name node
        $nameNode = $this->_astNode;
        while (get_class($nameNode) !== NamedTypeNode::class) {
            $nameNode = $nameNode->type;
        }

        return new FieldType(
            $nameNode->name->value,
            $isNullableObject,
            $inList,
            $isNullableList
        );
    }
}