<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;

interface FieldsFetchableInterface {
	/**
	 * @return FieldInterpretedType[]
	 */
	public function getFields();

}