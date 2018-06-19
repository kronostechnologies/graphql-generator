<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


trait DefaultValueFragmentTrait {
	/**
	 * @param object $value
	 * @return string
	 */
	protected function getDefaultValueFragment($value) {
		if ($value !== null) {
			return "'defaultValue' => '{$value}'";
		}

		return '';
	}
}