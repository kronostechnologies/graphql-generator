<?php


namespace GraphQLGen\Old\Generator\InterpretedTypes\Nested;


use GraphQLGen\Old\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\FieldTypeTrait;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;

class InputFieldInterpretedType implements FieldInterface {
	use NamedTypeTrait, DescribableTypeTrait, FieldTypeTrait;
}