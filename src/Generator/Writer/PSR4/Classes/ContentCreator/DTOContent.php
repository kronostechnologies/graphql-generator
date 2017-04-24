<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\FieldsFetchableInterface;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
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
		if ($this->getFragmentGenerator() instanceof TypeDeclarationFragmentGenerator) {
			/** @type TypeDeclarationFragmentGenerator $fragmentGenerator */
			$fragmentGenerator = $this->getFragmentGenerator();
			$interfaceNames = $fragmentGenerator->getTypeDeclaration()->getInterfacesNames();
			$interfaceNamesWithDTO = array_map(function ($interfaceName) {
				return $interfaceName . ClassComposer::DTO_CLASS_NAME_SUFFIX;
			}, $interfaceNames);

			return implode(", ", $interfaceNamesWithDTO);

		}

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