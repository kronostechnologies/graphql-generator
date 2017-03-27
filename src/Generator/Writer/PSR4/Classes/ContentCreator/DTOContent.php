<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\FieldsFetchableInterface;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\Classes\DTO;
use GraphQLGen\Generator\Writer\PSR4\TypeFormatter;

class DTOContent extends BaseContentCreator {

	/**
	 * @var DTO
	 */
	protected $_dtoClass;

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_typeGenerator;

	/**
	 * @return string
	 */
	public function getContent() {
		return "";
	}

	/**
	 * @param FieldInterpretedType $field
	 * @return string
	 */
	protected function getDocCommentFragment($field) {
		$typeFormatter = new TypeFormatter();
		$fieldDeclaration = $typeFormatter->resolveFieldTypeDocComment($field->getFieldType());

		return "/** @type {$fieldDeclaration} */";
	}

	/**
	 * @param FieldInterpretedType $field
	 * @return string
	 */
	protected function getVariableFragment($field) {
		return "public \${$field->getName()};";
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$content = "";

		if ($this->getTypeGenerator() instanceof FieldsFetchableInterface) {
			foreach ($this->getTypeGenerator()->getFields() as $field) {
				$content .= $this->getDocCommentFragment($field);
				$content .= $this->getVariableFragment($field);
			}
		}

		return $content;
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getDTOClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getDTOClass()->getClassName();
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}

	/**
	 * @param DTO $dtoClass
	 */
	public function setDTOClass($dtoClass) {
		$this->_dtoClass = $dtoClass;
	}

	/**
	 * @return DTO
	 */
	public function getDTOClass() {
		return $this->_dtoClass;
	}

	/**
	 * @return FragmentGeneratorInterface|FieldsFetchableInterface
	 */
	public function getTypeGenerator() {
		return $this->_typeGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface|FieldsFetchableInterface $typeGenerator
	 */
	public function setTypeGenerator($typeGenerator) {
		$this->_typeGenerator = $typeGenerator;
	}


}