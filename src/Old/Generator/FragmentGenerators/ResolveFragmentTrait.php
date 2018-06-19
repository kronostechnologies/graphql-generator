<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

trait ResolveFragmentTrait {
	/**
	 * @param StubFormatter $formatter
	 * @param TypeUsageInterpretedType $fieldType
	 * @param string $fieldName
	 * @param bool $forceResolve
	 * @return string
	 * @internal param InputFieldInterpretedType $field
	 */
	protected function getResolveFragment($formatter, $fieldType, $fieldName, $forceResolve = false) {
		if ($forceResolve || (!$fieldType->isPrimaryType() && !$formatter->canInterpretedTypeSkipResolver($fieldType->getTypeName()))) {
			return "'resolve' => " . $formatter->getResolveFragment($fieldType->getTypeName(), $fieldName);
		}

		return "";
	}
}
