<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class ClassesFactory {
	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return ObjectType
	 */
	public function createObjectTypeClass(BaseTypeGeneratorInterface $type) {
		$generatorClass = new ObjectType();
		$generatorClass->setClassName($type->getName() . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX);
		$generatorClass->setGeneratorType($type);

		return $generatorClass;
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return Resolver
	 */
	public function createResolverClass(BaseTypeGeneratorInterface $type) {
		$resolverClass = new Resolver();
		$resolverClass->setClassName($type->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX);
		$resolverClass->setGeneratorType($type);

		return $resolverClass;
	}

	/**
	 * @return TypeStore
	 */
	public function createTypeStoreClass() {
		$typeStoreClass = new TypeStore();
		$typeStoreClass->setClassName(ClassComposer::TYPE_STORE_CLASS_NAME);

		return $typeStoreClass;
	}
}