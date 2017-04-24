<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\DTO;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\ResolverFactory;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class ClassesFactory {
	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return ObjectType
	 */
	public function createObjectTypeClassWithFragmentGenerator($fragmentGenerator) {
		$generatorClass = new ObjectType();
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
	 * @return ClassComposer
	 */
	public function createClassComposer() {
		$classComposer = new ClassComposer($this);

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

}