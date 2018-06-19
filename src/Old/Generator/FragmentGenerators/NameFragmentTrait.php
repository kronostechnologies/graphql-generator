<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


trait NameFragmentTrait {
	/**
	 * @param string $fieldName
	 * @return string
	 */
	protected function getNameFragment($fieldName) {
		return "'name' => '{$fieldName}'";
	}
}