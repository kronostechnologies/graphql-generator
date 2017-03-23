<?php


namespace GraphQLGen\Generator\FragmentGenerators;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

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