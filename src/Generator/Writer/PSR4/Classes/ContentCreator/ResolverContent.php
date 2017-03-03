<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;

class ResolverContent extends BaseContentCreator {
	/**
	 * @var Resolver
	 */
	protected $_resolverClass;

	/**
	 * @var BaseTypeGeneratorInterface
	 */
	protected $_typeGenerator;

	/**
	 * @return Resolver
	 */
	public function getResolverClass() {
		return $this->_resolverClass;
	}

	/**
	 * @param Resolver $resolverClass
	 */
	public function setResolverClass($resolverClass) {
		$this->_resolverClass = $resolverClass;
	}

	public function getContent() {
		$typeGeneratorClass = $this->getTypeGeneratorClass();
		$contentAsLines = [];

		if(in_array($typeGeneratorClass, [InterfaceDeclaration::class, Type::class])) {
			/** @var InterfaceDeclaration|Type $typeGenerator */
			$typeGenerator = $this->getTypeGeneratorClass();

			foreach($typeGenerator->fields as $field) {
				$contentAsLines[] = "function resolve{$field->name}(\$root, \$args) { // ToDo: Implement }";
			}
		}

		return implode("\n", $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		return "";
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getResolverClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getResolverClass()->getClassName();
	}

	/**
	 * @return BaseTypeGeneratorInterface
	 */
	public function getTypeGenerator() {
		return $this->_typeGenerator;
	}

	/**
	 * @param BaseTypeGeneratorInterface $typeGenerator
	 */
	public function setTypeGenerator($typeGenerator) {
		$this->_typeGenerator = $typeGenerator;
	}

	/**
	 * @return string
	 */
	public function getTypeGeneratorClass() {
		return get_class($this->_typeGenerator);
	}
}