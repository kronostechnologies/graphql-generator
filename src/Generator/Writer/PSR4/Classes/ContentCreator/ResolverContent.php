<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\FieldsFetchableInterface;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Types\Union;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContent extends BaseContentCreator {
	/**
	 * @var Resolver
	 */
	protected $_resolverClass;

	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_typeGenerator;

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
		$typeGeneratorClass = $this->getTypeGeneratorClass();
		$contentAsLines = [];

        if ($this->getTypeGenerator() instanceof UnionFragmentGenerator) {
            $contentAsLines[] = "function resolve(\$value, \$context, \$info) { /** ToDo: Implement */ }";
        }

		if($this->getTypeGenerator() instanceof FieldsFetchableInterface) {
			/** @var InterfaceFragmentGenerator|TypeDeclarationFragmentGenerator $typeGenerator */
			$typeGenerator = $this->getTypeGenerator();

			foreach($typeGenerator->getFields() as $field) {
				if ($field->getFieldType()->isPrimaryType()) {
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
	public function getTypeGenerator() {
		return $this->_typeGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface $typeGenerator
	 */
	public function setTypeGenerator(FragmentGeneratorInterface $typeGenerator) {
		$this->_typeGenerator = $typeGenerator;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getTypeGeneratorClass() {
		if ($this->getTypeGenerator() === null) {
			throw new Exception("Internal Exception: Type generator is not defined for {$this->getResolverClass()->getClassName()}");
		}

		return get_class($this->_typeGenerator);
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}
}