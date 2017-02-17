<?php


namespace GraphQLGen\Generator\Types;


class ObjectTypeField
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var FieldType
     */
    public $fieldType;

    /**
     * @var ObjectTypeFieldArgument[]
     */
    public $arguments = [];
}