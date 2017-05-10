<?php


namespace GraphQLGen\Generator\Writer\Namespaced;


use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;

/**
 * PSR-4 helper for generating a field declaration, along with their variables.
 *
 * Class FieldDeclaration
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class FieldDeclaration {
	/**
	 * @var FieldInterpretedType
	 */
	protected $_field;

	/**
	 * @var bool
	 */
	protected $_showAnnotations;

	/**
	 * PSR4FieldDeclaration constructor.
	 * @param FieldInterpretedType $field
	 * @param bool $showAnnotations
	 */
	public function __construct($field, $showAnnotations) {
		$this->_field = $field;
		$this->_showAnnotations = $showAnnotations;
	}

	/**
	 * @return string[]
	 */
	private static function getPrimaryTypesAnnotationMappings() {
		return [
			'ID' => 'int',
			'Int' => 'int',
			'String' => 'string',
			'Float' => 'float',
			'Boolean' => 'bool',
		];
	}

	/**
	 * @return string
	 */
	public function getFieldDeclarationVariable() {
		$variableString = $this->getVariableString();

		if($this->_showAnnotations) {
			$annotationString = $this->getAnnotationString();

			return "{$annotationString}\n{$variableString}";
		}

		return "{$variableString}";
	}

	/**
	 * @return string
	 */
	protected function getVariableString() {
		return "public \${$this->_field->getName()};";
	}

	/**
	 * @return string
	 */
	protected function getAnnotationString() {
		return "/*** @var {$this->getAnnotationTypeFragment()} **/";
	}

	/**
	 * @return string
	 */
	protected function getAnnotationTypeFragment() {
		return $this->getAnnotationFieldNameFragment() . $this->getInListAnnotationFragment() . $this->getNullAnnotationFragment();
	}

	/**
	 * @return string
	 */
	protected function getAnnotationFieldNameFragment() {
		$primaryTypesMappings = self::getPrimaryTypesAnnotationMappings();

		if(key_exists($this->_field->getFieldType()->getTypeName(), $primaryTypesMappings)) {
			return $primaryTypesMappings[$this->_field->getFieldType()->getTypeName()];
		}

		return $this->_field->getFieldType()->getTypeName();
	}

	/**
	 * @return string
	 */
	protected function getInListAnnotationFragment() {
		return ($this->_field->getFieldType()->isInList() ? '[]' : '');
	}

	/**
	 * @return string
	 */
	protected function getNullAnnotationFragment() {
		return ($this->_field->getFieldType()->isTypeNullable() ? '|null' : '');
	}
}