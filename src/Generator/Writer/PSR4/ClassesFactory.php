<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Writer\PSR4\Classes\DTO;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class ClassesFactory {
	/**
	 * @param BaseTypeGenerator $type
	 * @return ObjectType
	 */
	public function createObjectTypeClass(BaseTypeGenerator $type) {
		$generatorClass = new ObjectType();
		$generatorClass->setClassName($type->getName() . ClassComposer::TYPE_DEFINITION_CLASS_NAME_SUFFIX);
		$generatorClass->setGeneratorType($type);

		return $generatorClass;
	}

	/**
	 * @param BaseTypeGenerator $type
	 * @return Resolver
	 */
	public function createResolverClass(BaseTypeGenerator $type) {
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
	 * @param BaseTypeGenerator $type
	 * @return DTO
	 */
	public function createDTOClass($type) {
		$dtoClass = new DTO();
		$dtoClass->setClassName($type->getName() . ClassComposer::DTO_CLASS_NAME_SUFFIX);
		$dtoClass->setGeneratorType($type);

		return $dtoClass;
	}

}