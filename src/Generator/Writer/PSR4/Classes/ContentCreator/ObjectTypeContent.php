<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;

class ObjectTypeContent extends BaseContentCreator {
	/**
	 * @var ObjectType
	 */
	protected $_objectTypeClass;

	/**
	 * @var FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	protected $_fragmentGenerator;

	/**
	 * @return ObjectType
	 */
	public function getObjectTypeClass() {
		return $this->_objectTypeClass;
	}

	/**
	 * @param ObjectType $objectTypeClass
	 */
	public function setObjectTypeClass($objectTypeClass) {
		$this->_objectTypeClass = $objectTypeClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$contentAsLines = [];
		$resolverName = $this->getFragmentGenerator()->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX;

		$contentAsLines[] = "public function __construct() {";
		if ($this->isResolverNecessary()) {
			$contentAsLines[] = " \$this->resolver = new {$resolverName}();";
		}

		if (get_class($this->getFragmentGenerator()) == ScalarFragmentGenerator::class) {
			$contentAsLines[] = 'parent::__construct();';
			$contentAsLines[] = $this->getFragmentGenerator()->generateTypeDefinition();
		} else {
			$contentAsLines[] = "parent::__construct(";
			$contentAsLines[] = $this->getFragmentGenerator()->generateTypeDefinition();
			$contentAsLines[] = ");";
		}

		$contentAsLines[] = "}";

		return implode(PHP_EOL, $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$variableDeclarationsAsLines = [];

		if ($this->isResolverNecessary()) {
			$variableDeclarationsAsLines[] = "public \$resolver;";
		}

		if ($this->getFragmentGenerator() instanceof VariablesDefiningGeneratorInterface) {
			$variableDeclarationsAsLines[] = $this->getFragmentGenerator()->getVariablesDeclarations();
		}


		return implode(PHP_EOL, $variableDeclarationsAsLines);
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getObjectTypeClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getObjectTypeClass()->getClassName();
	}

	/**
	 * @return FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	public function getFragmentGenerator() {
		return $this->_fragmentGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface|VariablesDefiningGeneratorInterface $fragmentGenerator
	 */
	public function setFragmentGenerator($fragmentGenerator) {
		$this->_fragmentGenerator = $fragmentGenerator;
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return $this->getObjectTypeClass()->getParentClassName();
	}

	protected function isResolverNecessary() {
		return in_array(get_class($this->getFragmentGenerator()), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class, UnionFragmentGenerator::class, ScalarFragmentGenerator::class]);
	}
}