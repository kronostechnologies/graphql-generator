<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;

trait DescriptionFragmentTrait {
	/**
	 * @param StubFormatter $formatter
	 * @param string|null $fieldDescription
	 * @return string
	 */
	protected function getDescriptionFragment($formatter, $fieldDescription) {
		if (empty($fieldDescription)) {
			return "";
		}

		return "'description' => '{$formatter->standardizeDescription($fieldDescription)}'";
	}
}