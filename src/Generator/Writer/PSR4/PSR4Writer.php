<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

/**
 * Writer entry point.
 *
 * Class PSR4Writer
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class PSR4Writer implements GeneratorWriterInterface {
	/**
	 * @var PSR4WriterContext
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
	 * @param PSR4WriterContext $context
	 * @param ClassesFactory $factory
	 */
	public function __construct(PSR4WriterContext $context, $factory = null) {
		$this->_context = $context;
		$this->_factory = $factory ?: new ClassesFactory();
	}

	public function initialize() {
		$this->_classComposer = $this->_factory->createClassComposer();
		$this->_classComposer->setClassMapper($this->_factory->createClassMapper());
		$this->_classComposer->getClassMapper()->setBaseNamespace($this->_context->namespace);
		$this->_classComposer->getClassMapper()->setInitialMappings();

		$this->_classComposer->generateUniqueTypeStore();
	}

	/**
	 * @param BaseTypeGenerator $type
	 * @return string|void
	 */
	public function generateFileForTypeGenerator($type) {
		$this->_classComposer->generateClassForGenerator($type);

		if ($this->getClassComposer()->generatorTypeIsInputType($type)) {
			$this->_classComposer->generateResolverForGenerator($type);
			$this->_classComposer->generateDTOForGenerator($type);
		}
	}

	public function finalize() {
		$writer = $this->_factory->createClassesWriter();
		$writer->setClassMapper($this->_classComposer->getClassMapper());
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
}