<?php


namespace GraphQLGen\Generator\Writer\Namespaced;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;

/**
 * Writer entry point.
 *
 * Class PSR4Writer
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class NamespacedWriter implements GeneratorWriterInterface {
	/**
	 * @var NamespacedWriterContext
	 */
	protected $_context;

	/**
	 * @var ClassComposer
	 */
	protected $_classComposer;

	/**
	 * @var ClassesFactory
	 */
	protected $_factory;

	/**
	 * PSR4Writer constructor.
	 * @param NamespacedWriterContext $context
	 * @param ClassesFactory $factory
	 */
	public function __construct(NamespacedWriterContext $context, $factory = null) {
		$this->_context = $context;
		$this->_factory = $factory ?: new ClassesFactory();
	}

	public function initialize() {
		$classMapper = $this->getConfiguredClassMapper();

		$this->setClassComposer($this->_factory->createClassComposer($this->_context->skipResolver));
		$this->getClassComposer()->setClassMapper($classMapper);

		$this->getClassComposer()->initializeTypeStore();
		$this->getClassComposer()->initializeResolverFactory();
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function generateFileForTypeGenerator($fragmentGenerator) {
		$this->getClassComposer()->generateTypeDefinitionForFragmentGenerator($fragmentGenerator);

		if ($this->getClassComposer()->isFragmentGeneratorForInputType($fragmentGenerator)) {
			$this->getClassComposer()->generateDTOForFragmentGenerator($fragmentGenerator);
		}

		if (!$this->_context->skipResolver && $this->getClassComposer()->isFragmentGeneratorForInputType($fragmentGenerator) || $fragmentGenerator instanceof UnionFragmentGenerator || $fragmentGenerator instanceof ScalarFragmentGenerator) {
			$this->getClassComposer()->generateResolverForFragmentGenerator($fragmentGenerator);
		}
	}

	public function finalize() {
		$writer = $this->_factory->createClassesWriter();
		$writer->setClassMapper($this->getClassComposer()->getClassMapper());
		$writer->setWriterContext($this->_context);
		$writer->writeClasses();
	}

	/**
	 * @return ClassComposer
	 */
	public function getClassComposer() {
		return $this->_classComposer;
	}

	/**
	 * @param ClassComposer $classComposer
	 */
	public function setClassComposer($classComposer) {
		$this->_classComposer = $classComposer;
	}

	/**
	 * @return ClassMapper
	 */
	protected function getConfiguredClassMapper() {
		$classMapper = $this->_factory->createClassMapper();
		$classMapper->setBaseNamespace($this->_context->namespace);
		$classMapper->setInitialMappings();

		return $classMapper;
	}
}