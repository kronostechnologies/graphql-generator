<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
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
	 * PSR4Writer constructor.
	 * @param PSR4WriterContext $context
	 */
	public function __construct(PSR4WriterContext $context) {
		$this->_context = $context;
	}

	public function initialize() {
		$this->_classComposer = new ClassComposer();
		$this->_classComposer->setClassMapper(new ClassMapper());
		$this->_classComposer->getClassMapper()->setTypeStore(new TypeStore());
		$this->_classComposer->getClassMapper()->setBaseNamespace($this->_context->namespace);
		$this->_classComposer->getClassMapper()->setInitialMappings();

		$this->_classComposer->generateUniqueTypeStore();
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string|void
	 */
	public function generateFileForTypeGenerator($type) {
		$this->_classComposer->generateClassForGenerator($type);

		if ($this->getClassComposer()->generatorTypeSupportsResolver($type)) {
			$this->_classComposer->generateResolverForGenerator($type);
		}
	}

	public function finalize() {
		$this->_classComposer->writeClasses();
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