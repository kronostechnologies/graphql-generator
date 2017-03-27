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
	public function setTypeStoreClass(TypeStore $typeStoreClass) {
		$this->_typeStoreClass = $typeStoreClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$lineSeparatedContent = [];

		foreach($this->getTypeStoreClass()->getTypesToImplement() as $typeToImplement) {
			$lineSeparatedContent[] = $this->getFunctionForType($typeToImplement);
		}

		return implode(PHP_EOL, $lineSeparatedContent);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$lineSeparatedVariables = [];

		foreach($this->getTypeStoreClass()->getTypesToImplement() as $typeToImplement) {
			$lineSeparatedVariables[] = $this->getVariableForType($typeToImplement);
		}

		return implode(PHP_EOL, $lineSeparatedVariables);
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getTypeStoreClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getTypeStoreClass()->getClassName();
	}

	/**
	 * @param ObjectType $type
	 * @return string
	 */
	protected function getFunctionForType(ObjectType $type) {
		return "public static function {$type->getFragmentGenerator()->getName()}() { return self::\${$type->getFragmentGenerator()->getName()} ?: (self::\${$type->getFragmentGenerator()->getName()} = new {$type->getClassName()}()); }";
	}

	/**
	 * @param ObjectType $type
	 * @return string
	 */
	protected function getVariableForType(ObjectType $type) {
		return "private static \${$type->getFragmentGenerator()->getName()};";
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return "";
	}
}