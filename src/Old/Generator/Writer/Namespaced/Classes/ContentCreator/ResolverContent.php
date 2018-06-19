<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator;


use Exception;
use GraphQLGen\Old\Generator\FragmentGenerators\FieldsFetchableInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Old\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Old\Generator\InterpretedTypes\Nested\FieldInterpretedType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\Resolver;

class ResolverContent extends BaseContentCreator {
	/**
	 * @var Resolver
	 */
	protected $_resolverClass;

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_fragmentGenerator;

	/**
	 * @return Resolver
	 */
	public function getResolverClass() {
		return $this->_resolverClass;
	}

	/**
	 * @param Resolver $resolverClass
	 */
	public function setResolverClass($resolverClass) {
		$this->_resolverClass = $resolverClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$contentAsLines = [];

		if ($this->getFragmentGenerator() instanceof InterfaceFragmentGenerator) {
			$contentAsLines[] = "function resolveType(\$value) { /** ToDo: Implement */ }";
		}

        if ($this->getFragmentGenerator() instanceof UnionFragmentGenerator) {
            $contentAsLines[] = "function resolve(\$value, \$context, \$info) { /** ToDo: Implement */ }";
        }

        if ($this->getFragmentGenerator() instanceof ScalarFragmentGenerator) {
			$contentAsLines[] = "public function serialize(\$value) { /** ToDo: Implement */ }";
			$contentAsLines[] = "public function parseValue(\$value) { /** ToDo: Implement */ }";
			$contentAsLines[] = "public function parseLiteral(\$value) { /** ToDo: Implement */ }";
        }

		if($this->getFragmentGenerator() instanceof FieldsFetchableInterface) {
			/** @var InterfaceFragmentGenerator|TypeDeclarationFragmentGenerator $typeGenerator */
			$typeGenerator = $this->getFragmentGenerator();

			foreach($typeGenerator->getFields() as $field) {
				$typeHasArguments = ($field instanceof FieldInterpretedType) && !empty($field->getArguments());
				if (!$typeHasArguments &&
					($field->getFieldType()->isPrimaryType() ||
					$typeGenerator->getFormatter()->canInterpretedTypeSkipResolver($field->getFieldType()->getTypeName()))) {
					continue;
				}

			    $fieldNameFirstLetterCapped = ucwords($field->getName());
				$contentAsLines[] = "function resolve{$fieldNameFirstLetterCapped}(\$root, \$args) { /** ToDo: Implement */ }";
			}
		}

		return implode(PHP_EOL, $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		return "";
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getResolverClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getResolverClass()->getClassName();
	}

	/**
	 * @return FragmentGeneratorInterface
	 */
	public function getFragmentGenerator() {
		return $this->_fragmentGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function setFragmentGenerator($fragmentGenerator) {
		$this->_fragmentGenerator = $fragmentGenerator;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getTypeGeneratorClass() {
		if ($this->getFragmentGenerator() === null) {
			throw new Exception("Internal Exception: Type generator is not defined for {$this->getResolverClass()->getClassName()}");
		}

		return get_class($this->_fragmentGenerator);
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}
}