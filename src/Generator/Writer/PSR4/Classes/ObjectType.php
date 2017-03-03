<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;

class ObjectType extends SingleClass {

	/**
	 * @var Resolver
	 */
	protected $_associatedResolver;

	/**
	 * @var
	 */
	protected $_generatorType;


	public function getContent() {
		throw new Exception("ToDo: Implement");
	}

	/**
	 * @return Resolver
	 */
	public function getAssociatedResolver() {
		return $this->_associatedResolver;
	}

	/**
	 * @param Resolver $associatedResolver
	 */
	public function setAssociatedResolver(Resolver $associatedResolver) {
		$this->_associatedResolver = $associatedResolver;
	}

	/**
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
	 */
	public function setGeneratorType(BaseTypeGeneratorInterface $generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return ObjectTypeContent
	 */
	public function getContentCreator() {
		$objectTypeContent = new ObjectTypeContent();
		$objectTypeContent->setObjectTypeClass($this);

		return $objectTypeContent;
	}
}