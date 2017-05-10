<?php


namespace GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\FieldsFetchableInterface;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Writer\Namespaced\Classes\DTO;
use GraphQLGen\Generator\Writer\Namespaced\TypeFormatter;

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
		if (!$this->getDTOClass()->shouldRenderContent()) {
			return "";
		}

		$content = "";

		if ($this->getFragmentGenerator() instanceof FieldsFetchableInterface) {
			foreach ($this->getFragmentGenerator()->getFields() as $field) {
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
	public function getFragmentGenerator() {
		return $this->_typeGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface|FieldsFetchableInterface $fragmentGenerator
	 */
	public function setFragmentGenerator($fragmentGenerator) {
		$this->_typeGenerator = $fragmentGenerator;
	}
}