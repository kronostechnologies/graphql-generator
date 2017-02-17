<?php


namespace GraphQLGen\Generator\Types;


class EnumTypeValue {
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * EnumTypeValue constructor.
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }
}