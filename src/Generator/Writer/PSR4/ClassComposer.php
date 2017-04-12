<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\FragmentGenerators\DependentFragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;

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
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function generateTypeDefinitionForFragmentGenerator($fragmentGenerator) {
		// Create generator class
		$typeDefinitionClass = $this->createConfiguredTypeDefinitionClass($fragmentGenerator);

		// Add resolver dependency
		$this->setupTypeDefinitionClassDependencies($typeDefinitionClass);

		// Map class
		$this->getClassMapper()->mapDependencyNameToClass($fragmentGenerator->getName(), $typeDefinitionClass);
		$this->getClassMapper()->registerTypeStoreEntry($fragmentGenerator->getName(), $typeDefinitionClass);
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return Classes\ObjectType
	 */
	protected function createConfiguredTypeDefinitionClass($fragmentGenerator) {
		$fragmentGeneratorNS = $this->getClassMapper()->getNamespaceForFragmentGenerator($fragmentGenerator);
		$fragmentGeneratorParentDep = $this->getClassMapper()->getParentDependencyForFragmentGenerator($fragmentGenerator);

		$typeDefinitionClass = $this->getFactory()->createObjectTypeClassWithFragmentGenerator($fragmentGenerator);
		$typeDefinitionClass->setNamespace($fragmentGeneratorNS);
		$typeDefinitionClass->setParentClassName($fragmentGeneratorParentDep);

		return $typeDefinitionClass;
	}

	/**
	 * @param Classes\ObjectType $typeDefinitionClass
	 */
	protected function setupTypeDefinitionClassDependencies($typeDefinitionClass) {
		$fragmentGenerator = $typeDefinitionClass->getFragmentGenerator();

		if ($this->isFragmentGeneratorForInputType($fragmentGenerator) || $fragmentGenerator instanceof UnionFragmentGenerator) {
			$typeDefinitionClass->addDependency($fragmentGenerator->getName() . self::RESOLVER_CLASS_NAME_SUFFIX);
		}

		$typeDefinitionClass->addDependency(self::TYPE_STORE_CLASS_NAME);
		$typeDefinitionClass->addDependency(self::TYPE_CLASS_NAME);
		$typeDefinitionClass->addDependency($typeDefinitionClass->getParentClassName());
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function generateResolverForFragmentGenerator(FragmentGeneratorInterface $fragmentGenerator) {
		// Create resolver class
		$resolverClass = $this->createConfiguredResolverClass($fragmentGenerator);

		// Map class
		$this->getClassMapper()->mapDependencyNameToClass($resolverClass->getClassName(), $resolverClass);
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 *
	 * @return Classes\Resolver
	 */
	protected function createConfiguredResolverClass(FragmentGeneratorInterface $fragmentGenerator) {
		$resolverClass = $this->getFactory()->createResolverClassWithFragmentGenerator($fragmentGenerator);
		$resolverClass->setNamespace($this->getClassMapper()->getResolverNamespaceFromGenerator($fragmentGenerator));

		return $resolverClass;
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 */
	public function generateDTOForFragmentGenerator(FragmentGeneratorInterface $fragmentGenerator) {
		// Create DTO class
		$dtoClass = $this->createConfiguredDTOClass($fragmentGenerator);

		// Map class
		$this->getClassMapper()->mapDependencyNameToClass($dtoClass->getClassName(), $dtoClass);
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return Classes\DTO
	 */
	protected function createConfiguredDTOClass(FragmentGeneratorInterface $fragmentGenerator) {
		$dtoClass = $this->getFactory()->createDTOClassWithFragmentGenerator($fragmentGenerator);
		$dtoClass->setNamespace($this->getClassMapper()->getDTONamespaceFromGenerator($fragmentGenerator));

		return $dtoClass;
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return bool
	 */
	public function isFragmentGeneratorForInputType($type) {
		return in_array(get_class($type), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class]);
	}

	public function initializeTypeStore() {
		// Create type store class
		$typeStoreClass = $this->createConfiguredTypeStoreClass();

		// Sets type store
		$this->getClassMapper()->setTypeStore($typeStoreClass);

		// Map class
		$this->getClassMapper()->mapDependencyNameToClass($typeStoreClass->getClassName(), $typeStoreClass);
	}

	/**
	 * @return Classes\TypeStore
	 */
	protected function createConfiguredTypeStoreClass() {
		$typeStoreClass = $this->getFactory()->createTypeStoreClass();
		$typeStoreClass->setNamespace($this->getClassMapper()->getBaseNamespace());

		return $typeStoreClass;
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