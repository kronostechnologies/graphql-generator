<?php


namespace GraphQLGen\Generator\InterpretedTypes\Nested;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\FieldTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;

class InputFieldInterpretedType implements FieldInterface {
	use NamedTypeTrait, DescribableTypeTrait, FieldTypeTrait;
}