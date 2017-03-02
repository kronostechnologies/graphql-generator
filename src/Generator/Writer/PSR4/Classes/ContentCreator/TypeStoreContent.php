<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator;


use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class TypeStoreContent {
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
	public function generateContent() {
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
}