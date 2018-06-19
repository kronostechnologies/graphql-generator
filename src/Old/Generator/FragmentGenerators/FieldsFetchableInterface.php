<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterface;

interface FieldsFetchableInterface {
	/**
	 * @return FieldInterface[]
	 */
	public function getFields();

}