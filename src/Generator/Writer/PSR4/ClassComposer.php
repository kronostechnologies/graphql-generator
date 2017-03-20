<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;

/**
 * Generates individual classes entities.
 *
 * Class ClassComposer
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class ClassComposer {
	const DTO_CLASS_NAME_SUFFIX = 'DTO';
	const RESOLVER_CLASS_NAME_SUFFIX = 'Resolver';
	const TYPE_DEFINITION_CLASS_NAME_SUFFIX = 'Type';
	const TYPE_STORE_CLASS_NAME = 'TypeStore';
	const TYPE_CLASS_NAME = 'Type';

	/**
	 * @var ClassMapper
	 */
	protected $_classMapper;

	/**
	 * @var ClassesFactory
	 */
	protected $_factory;

	/**
	 * ClassComposer constructor.
	 * @param ClassesFactory $factory
	 */
	public function __construct($factory = null) {
		$this->_factory = $factory ?: new ClassesFactory();
	}

	/**
	 * @param BaseTypeGenerator $type
	 */
	public function generateClassForGenerator(BaseTypeGenerator $type) {
		// Create generator class
		$generatorClass = $this->getFactory()->createObjectTypeClass($type);
		$generatorClass->setNamespace($this->getClassMapper()->getNamespaceForGenerator($type));
		$generatorClass->setParentClassName($this->getClassMapper()->getParentDependencyForGenerator($type));

		// Add dependency to TypeStore
		$this->getClassMapper()->getTypeStore()->addDependency($type->getName());

		// Add resolver dependency
		if ($this->generatorTypeSupportsResolverOrDTO($type)) {
			$generatorClass->addDependency($type->getName() . self::RESOLVER_CLASS_NAME_SUFFIX);
		}

		$generatorClass->addDependency(self::TYPE_STORE_CLASS_NAME);
		$generatorClass->addDependency(self::TYPE_CLASS_NAME);
		$generatorClass->addDependency($generatorClass->getParentClassName());

		// Map class
		$this->getClassMapper()->mapClass($type->getName(), $generatorClass, true);
	}


	/**
	 * @param BaseTypeGenerator $type
	 */
	public function generateResolverForGenerator(BaseTypeGenerator $type) {
		// Create resolver class
		$resolverClass = $this->getFactory()->createResolverClass($type);
		$resolverClass->setNamespace($this->getClassMapper()->getResolverNamespaceFromGenerator($type));

		// Map class
		$this->getClassMapper()->mapClass($resolverClass->getClassName(), $resolverClass, false);
	}

	/**
	 * @param BaseTypeGenerator $type
	 */
	public function generateDTOForGenerator(BaseTypeGenerator $type) {
		// Create DTO class
		$dtoClass = $this->getFactory()->createDTOClass($type);
		$dtoClass->setNamespace($this->getClassMapper()->getDTONamespaceFromGenerator($type));

		// Fetches dependencies
		/*foreach ($type->getDependencies() as $dependency) {
			$dtoClass->addDependency($dependency);
		}*/

		// Map class
		$this->getClassMapper()->mapClass($dtoClass->getClassName(), $dtoClass, false);
	}

	/**
	 * @param BaseTypeGenerator $type
	 * @return bool
	 */
	public function generatorTypeSupportsResolverOrDTO(BaseTypeGenerator $type) {
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
	 * @return ClassesFactory
	 */
	public function getFactory() {
		return $this->_factory;
	}
}