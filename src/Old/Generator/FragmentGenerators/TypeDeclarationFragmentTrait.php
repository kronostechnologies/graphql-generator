<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


use GraphQLGen\Old\Generator\Formatters\StubFormatter;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

trait TypeDeclarationFragmentTrait {
	/**
	 * @param StubFormatter $formatter
	 * @param TypeUsageInterpretedType $type
	 * @return string
	 */
	protected function getTypeDeclarationFragment($formatter, $type) {
		return "'type' => " . $formatter->getFieldTypeDeclaration($type);
	}
}