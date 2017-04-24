<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Nested\FieldInterface;
use GraphQLGen\Generator\Writer\PSR4\ClassComposer;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\ResolverFactory;

class ResolverFactoryContent extends BaseContentCreator {
	/**
	 * @var ResolverFactory
	 */
	protected $_resolverFactoryClass;

	/**
	 * @return string
	 */
	public function getContent() {
		$lines = "";

		foreach ($this->getResolverFactoryClass()->getTypeResolversToAdd() as $typeResolverToAdd) {
			$lines .= $this->getResolveFunctionForType($typeResolverToAdd);
		}

		return $lines;
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		return '';
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getResolverFactoryClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getResolverFactoryClass()->getClassName();
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return '';
	}

	/**
	 * @return ResolverFactory
	 */
	public function getResolverFactoryClass() {
		return $this->_resolverFactoryClass;
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 */
	public function getResolveFunctionForType($type) {
		return "public function create{$type->getName()}Resolver() { return new {$type->getName()}Resolver(); }";
	}

	/**
	 * @param ResolverFactory $resolverFactory
	 */
	public function setResolverFactoryClass($resolverFactory) {
		$this->_resolverFactoryClass = $resolverFactory;
	}
}