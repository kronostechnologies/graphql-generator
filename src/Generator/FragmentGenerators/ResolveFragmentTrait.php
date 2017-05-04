<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

trait ResolveFragmentTrait {
	/**
	 * @param StubFormatter $formatter
	 * @param TypeUsageInterpretedType $fieldType
	 * @param string $fieldName
	 * @return string
	 * @internal param InputFieldInterpretedType $field
	 */
	protected function getResolveFragment($formatter, $fieldType, $fieldName) {
		if (!$fieldType->isPrimaryType() && !$formatter->isScalarOrEnumType($fieldType->getTypeName())) {
			return "'resolve' => " . $formatter->getResolveFragment($fieldName);
		}

		return "";
	}
}