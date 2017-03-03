<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\SubTypes\Field;

/**
 * PSR-4 helper for generating a field declaration, along with their variables.
 *
 * Class FieldDeclaration
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class FieldDeclaration {
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
	 * @return string[]
	 */
	private static function getPrimaryTypesAnnotationMappings() {
		return [
			'ID' => 'int',
			'Int' => 'int',
			'String' => 'string',
			'Float' => 'float',
			'Bool' => 'bool',
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
		return "public \${$this->_field->name};";
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

		if(key_exists($this->_field->fieldType->typeName, $primaryTypesMappings)) {
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
}