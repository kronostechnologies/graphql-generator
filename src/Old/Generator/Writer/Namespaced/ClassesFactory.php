<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced;


use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\DTO;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ObjectType;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\Resolver;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ResolverFactory;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\TypeStore;

class ClassesFactory {
    /**
     * @param FragmentGeneratorInterface $fragmentGenerator
     * @param bool $skipResolver
     * @return ObjectType
     */
	public function createObjectTypeClassWithFragmentGenerator($fragmentGenerator, $skipResolver) {
		$generatorClass = new ObjectType($skipResolver);
		$generatorClass->setClassName($fragmentGenerator->getName() . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX);
		$generatorClass->setFragmentGenerator($fragmentGenerator);

		return $generatorClass;
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return Resolver
	 */
	public function createResolverClassWithFragmentGenerator($fragmentGenerator) {
		$resolverClass = new Resolver();
		$resolverClass->setClassName($fragmentGenerator->getName() . ClassComposer::RESOLVER_CLASS_NAME_SUFFIX);
		$resolverClass->setFragmentGenerator($fragmentGenerator);

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

	/**
	 * @return ClassStubFile
	 */
	public function createStubFile() {
		$stubFile = new ClassStubFile();

		return $stubFile;
	}

    /**
     * @param bool $skipResolver
     * @return ClassComposer
     */
	public function createClassComposer($skipResolver) {
		$classComposer = new ClassComposer($this, $skipResolver);

		return $classComposer;
	}

	/**
	 * @return ClassesWriter
	 */
	public function createClassesWriter() {
		$classesWriter = new ClassesWriter($this);

		return $classesWriter;
	}

	/**
	 * @return ClassMapper
	 */
	public function createClassMapper() {
		$classMapper = new ClassMapper();

		return $classMapper;
	}

	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return DTO
	 */
	public function createDTOClassWithFragmentGenerator($fragmentGenerator) {
		$dtoClass = new DTO();
		$dtoClass->setClassName($fragmentGenerator->getName() . ClassComposer::DTO_CLASS_NAME_SUFFIX);
		$dtoClass->setFragmentGenerator($fragmentGenerator);

		return $dtoClass;
	}

	/**
	 * @return ResolverFactory
	 */
	public function createResolverFactoryClass() {
		$resolverFactoryClass = new ResolverFactory();
		$resolverFactoryClass->setClassName(ClassComposer::RESOLVER_FACTORY);

		return $resolverFactoryClass;
  }
  
	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return DTO
	 */
	public function createTraitDTOClassWithFragmentGenerator($fragmentGenerator) {
		$dtoClass = new DTO();
		$dtoClass->setClassName($fragmentGenerator->getName() . ClassComposer::INTERFACE_TRAIT_SUFFIX);
		$dtoClass->setFragmentGenerator($fragmentGenerator);

		return $dtoClass;
	}

}