<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\Field;

class PSR4FieldDeclaration {
	/**
	 * @var Field
	 */
	protected $_field;

	/**
	 * @var bool
	 */
	protected $_showAnnotations;


	/**
	 * PSR4FieldDeclaration constructor.
	 * @param Field $field
	 * @param bool $showAnnotations
	 */
	public function __construct($field, $showAnnotations) {
		$this->_field = $field;
		$this->_showAnnotations = $showAnnotations;
	}

	/**
	 * @return string
	 */
	public function getFieldDeclaration() {
		$variableString = $this->getVariableString();

		if ($this->_showAnnotations) {
			$annotationString = $this->getAnnotationString();

			return "{$annotationString}\n{$variableString}";
		}

		return "{$variableString}";
	}

	/**
	 * @return string
	 */
	public function getVariableString() {
		return "protected \${$this->_field->name};";
	}

	/**
	 * @return string
	 */
	public function getAnnotationString() {
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

		if (key_exists($this->_field->fieldType->typeName, $primaryTypesMappings)) {
			return $primaryTypesMappings[$this->_field->fieldType->typeName];
		}

		return $this->_field->fieldType->typeName;
	}

	/**
	 * @return string
	 */
	protected function getInListAnnotationFragment() {
		return ($this->_field->fieldType->inList ? '[]' : '');
	}

	/**
	 * @return string
	 */
	protected function getNullAnnotationFragment() {
		return ($this->_field->fieldType->isTypeNullable ? '|null' : '');
	}

	/**
	 * @return array
	 */
	public static function getPrimaryTypesAnnotationMappings() {
		return [
			'ID' => 'int',
			'Int' => 'int',
			'String' => 'string',
			'Float' => 'float',
			'Bool' => 'bool',
			'Boolean' => 'bool',
		];
	}
}