<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

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
			return "'resolve' => " . $formatter->getResolveFragment($fieldName);
		}

		return "";
	}
}