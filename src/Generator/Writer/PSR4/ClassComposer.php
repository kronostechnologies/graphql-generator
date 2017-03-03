<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\TypeStoreContent;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class ClassComposer {
	const RESOLVER_CLASS_NAME_SUFFIX = 'Resolver';
	const TYPE_DEFINITION_CLASS_NAME_SUFFIX = 'TypeDef';
	const TYPE_STORE_CLASS_NAME = 'TypeStore';

	/**
	 * @var ClassMapper
	 */
	protected $_classMapper;

	/**
	 * @param ClassMapper $classMapper
	 */
	public function __construct(ClassMapper $classMapper) {
		$this->_classMapper = $classMapper;
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 */
	public function generateClassForGenerator(BaseTypeGeneratorInterface $type) {
		$generatorClass = new ObjectType();
		$generatorClass->setNamespace($this->getClassMapper()->getNamespaceForGenerator($type));
		$generatorClass->setClassName($type->getName() . self::TYPE_DEFINITION_CLASS_NAME_SUFFIX);

		$this->getClassMapper()->addClass($generatorClass);
		$this->getClassMapper()->resolveDependency($generatorClass->getClassName(), $generatorClass->getFullQualifiedName());
		$this->getClassMapper()->getTypeStore()->addTypeImplementation($generatorClass);
	}

	/**
	 * @param ObjectType $objectType
	 */
	public function generateResolverForObjectType(ObjectType $objectType) {
		$resolverClass = new Resolver();
		$resolverClass->setNamespace($this->getClassMapper()->getResolverNamespaceFromGenerator($objectType->getGeneratorType()));
		$resolverClass->setClassName($objectType->getGeneratorType()->getName() . self::RESOLVER_CLASS_NAME_SUFFIX);

		$this->getClassMapper()->addClass($resolverClass);
		$this->getClassMapper()->resolveDependency($resolverClass->getClassName(), $resolverClass->getFullQualifiedName());
	}

	public function generateTypeStore() {
		$typeStoreClass = new TypeStore();
		$typeStoreClass->setNamespace($this->getClassMapper()->getBaseNamespace());
		$typeStoreClass->setClassName(self::TYPE_STORE_CLASS_NAME);

		$typeStoreContent = new TypeStoreContent();
		$typeStoreContent->setTypeStoreClass($typeStoreClass);

		$this->getClassMapper()->setTypeStore($typeStoreClass);
		$this->getClassMapper()->addClass($typeStoreClass);
		$this->getClassMapper()->resolveDependency($typeStoreClass->getClassName(), $typeStoreClass->getFullQualifiedName());
	}

	public function writeClasses() {
		foreach ($this->_classMapper->getClasses() as $class) {
			$contentCreator = $class->getContentCreator();

			// Resolve dependencies
			$resolvedDependenciesAsLines = [];
			foreach ($class->getDependencies() as $dependency) {
				$resolvedDependenciesAsLines[] = $this->getClassMapper()->getResolvedDependency($dependency);
			}
			$resolvedDependenciesAsLines = array_unique($resolvedDependenciesAsLines);
			$resolvedDependencies = implode("\n", $resolvedDependenciesAsLines);

			// ToDo: Writer content formatting

			$stubFileName = $this->getClassMapper()->getStubFilenameForClass($class);
			$stubFile = new ClassStubFile(null);
			$stubFile->writeContent($contentCreator->getContent());
			$stubFile->writeOrStripNamespace($contentCreator->getNamespace()); // ToDo: Move if here
			$stubFile->writeClassName($contentCreator->getClassName());
			$stubFile->writeVariablesDeclarations($contentCreator->getVariables());
			$stubFile->writeDependenciesDeclaration($resolvedDependencies);

			// ToDo: Save stub file
		}

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
}