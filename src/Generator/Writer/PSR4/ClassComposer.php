<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;

/**
 * Generates individual classes entities.
 *
 * Class ClassComposer
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class ClassComposer {
	const RESOLVER_CLASS_NAME_SUFFIX = 'Resolver';
	const TYPE_DEFINITION_CLASS_NAME_SUFFIX = 'Type';
	const TYPE_STORE_CLASS_NAME = 'TypeStore';
	const TYPE_CLASS_NAME = 'Type';

	/**
	 * @var ClassMapper
	 */
	protected $_classMapper;

	/**
	 * @var PSR4Factory
	 */
	protected $_factory;

	/**
	 * ClassComposer constructor.
	 * @param PSR4Factory $factory
	 */
	public function __construct($factory = null) {
		$this->_factory = $factory ?: new PSR4Factory();
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 */
	public function generateClassForGenerator(BaseTypeGeneratorInterface $type) {
		// Create generator class
		$generatorClass = $this->getFactory()->createObjectTypeClass($type);
		$generatorClass->setNamespace($this->getClassMapper()->getNamespaceForGenerator($type));

		// Add dependency to TypeStore
		$this->getClassMapper()->getTypeStore()->addDependency($type->getName());

		// Add resolver dependency
		if ($this->generatorTypeSupportsResolver($type)) {
			$generatorClass->addDependency($type->getName() . self::RESOLVER_CLASS_NAME_SUFFIX);
		}
		$generatorClass->addDependency(self::TYPE_STORE_CLASS_NAME);
		$generatorClass->addDependency(self::TYPE_CLASS_NAME);

		// Map class
		$this->getClassMapper()->mapClass($type->getName(), $generatorClass, true);
	}


	/**
	 * @param BaseTypeGeneratorInterface $type
	 */
	public function generateResolverForGenerator(BaseTypeGeneratorInterface $type) {
		// Create resolver class
		$resolverClass = $this->getFactory()->createResolverClass($type);
		$resolverClass->setNamespace($this->getClassMapper()->getResolverNamespaceFromGenerator($type));

		// Map class
		$this->getClassMapper()->mapClass($resolverClass->getClassName(), $resolverClass, false);
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return bool
	 */
	public function generatorTypeSupportsResolver(BaseTypeGeneratorInterface $type) {
		return in_array(get_class($type), [InterfaceDeclaration::class, Type::class]);
	}

	public function generateUniqueTypeStore() {
		// Create type store class
		$typeStoreClass = $this->getFactory()->createTypeStoreClass();
		$typeStoreClass->setNamespace($this->getClassMapper()->getBaseNamespace());

		// Sets type store
		$this->getClassMapper()->setTypeStore($typeStoreClass);

		// Map class
		$this->getClassMapper()->mapClass($typeStoreClass->getClassName(), $typeStoreClass, false);
	}



	/**
	 * @return ClassMapper
	 */
	public function getClassMapper() {
		return $this->_classMapper;
	}

	/**
	 * @param ClassMapper $classMapper
	 */
	public function setClassMapper($classMapper) {
		$this->_classMapper = $classMapper;
	}

	/**
	 * @return PSR4Factory
	 */
	public function getFactory() {
		return $this->_factory;
	}
}