<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterface;

interface FieldsFetchableInterface {
	/**
	 * @return FieldInterface[]
	 */
	public function getFields();

}