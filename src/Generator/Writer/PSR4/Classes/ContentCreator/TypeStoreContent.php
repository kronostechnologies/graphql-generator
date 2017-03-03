<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class TypeStoreContent extends BaseContentCreator {
	/**
	 * @var TypeStore
	 */
	protected $_typeStoreClass;

	/**
	 * @return TypeStore
	 */
	public function getTypeStoreClass() {
		return $this->_typeStoreClass;
	}

	/**
	 * @param TypeStore $typeStoreClass
	 */
	public function setTypeStoreClass($typeStoreClass) {
		$this->_typeStoreClass = $typeStoreClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$lineSeparatedContent = [];

		foreach ($this->getTypeStoreClass()->getTypesToImplement() as $typeToImplement) {
			$lineSeparatedContent[] = $this->getFunctionForType($typeToImplement);
		}

		return implode("\n", $lineSeparatedContent);
	}

	/**
	 * @param ObjectType $type
	 * @return string
	 */
	protected function getFunctionForType(ObjectType $type) {
		return "public static function \${$type->getClassName()}() { if (self::\${$type->getClassName()} === null) { self::\${$type->getClassName()} = new {$type->getClassName()}(); } return self::\${$type->getClassName()}; }";
	}

	public function getVariables() {
		// TODO: Implement getVariables() method.
	}

	public function getNamespace() {
		// TODO: Implement getNamespace() method.
	}

	public function getClassName() {
		// TODO: Implement getClassName() method.
	}
}