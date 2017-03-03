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


	public function generateClassForGenerator(BaseTypeGeneratorInterface $type) {
		$generatorClass = new ObjectType();
		$generatorClass->setNamespace($this->getClassMapper()->getNamespaceForGenerator($type));
		$generatorClass->setClassName($type->getName() . self::TYPE_DEFINITION_CLASS_NAME_SUFFIX);

		$this->_classMapper->addClass($generatorClass);
		$this->_classMapper->resolveDependency($generatorClass->getClassName(), $generatorClass->getFullQualifiedName());
		$this->_classMapper->getTypeStore()->addTypeImplementation($generatorClass);
	}

	public function generateResolverForObjectType(ObjectType $objectType) {
		$resolverClass = new Resolver();
		$resolverClass->setNamespace($this->getClassMapper()->getResolverNamespaceFromGenerator($objectType->getGeneratorType()));
		$resolverClass->setClassName($objectType->getGeneratorType()->getName() . self::RESOLVER_CLASS_NAME_SUFFIX);

		$this->_classMapper->addClass($resolverClass);
		$this->_classMapper->resolveDependency($resolverClass->getClassName(), $resolverClass->getFullQualifiedName());
	}

	public function generateTypeStore() {
		$typeStoreClass = new TypeStore();
		$typeStoreClass->setNamespace($this->getClassMapper()->getBaseNamespace());
		$typeStoreClass->setClassName(self::TYPE_STORE_CLASS_NAME);

		$typeStoreContent = new TypeStoreContent();
		$typeStoreContent->setTypeStoreClass($typeStoreClass);

		$this->_classMapper->setTypeStore($typeStoreClass);
		$this->_classMapper->addClass($typeStoreClass);
		$this->_classMapper->resolveDependency($typeStoreClass->getClassName(), $typeStoreClass->getFullQualifiedName());
	}

	public function writeClasses() {
		foreach ($this->_classMapper->getClasses() as $class) {
			$contentCreator = $class->getContentCreator();

			$stubFileName = $this->getClassMapper()->getStubFilenameForClass($class);

			// ToDo: Open stub file
			// ToDo: Write content
			// ToDo: Write namespace
			// ToDo: Write class name
			// ToDo: Write variables
			// ToDo: Write type definition
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