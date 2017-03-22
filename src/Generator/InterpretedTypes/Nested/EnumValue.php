<?php


namespace GraphQLGen\Generator\InterpretedTypes\Nested;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;

class EnumValue extends NestedInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;
}