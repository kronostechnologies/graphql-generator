<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced;


use GraphQLGen\Old\Generator\Writer\StubFile;

/**
 * StubFile with special PSR-4 annotations.
 *
 * Class ClassStubFile
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class ClassStubFile extends StubFile {
	const DUMMY_CLASSNAME = "ClassName";
	const DUMMY_NAMESPACE = "LocalNamespace";
	const CLASS_QUALIFIER = "@ClassQualifier";
	const USED_TRAITS = "// @generate:UsedTraits";
	const VARIABLES_DECLARATION = "// @generate:Variables";
	const EXTENDS_CLASSNAME = "ParentClass";

	/**
	 * @return null|string
	 */
	public function getNamespaceDeclarationLine() {
		return $this->getLineWithText(self::DUMMY_NAMESPACE);
	}

	/**
	 * @return null|string
	 */
	public function getClassNameLine() {
		return $this->getLineWithText(self::DUMMY_CLASSNAME);
	}

	/**
	 * @return null|string
	 */
	public function getExtendsClassNameLine() {
		return $this->getLineWithText(self::EXTENDS_CLASSNAME);
	}

	/**
	 * @return null|string
	 */
	public function getVariablesDeclarationLine() {
		return $this->getLineWithText(self::VARIABLES_DECLARATION);
	}

	/**
	 * @return null|string
	 */
	public function getClassQualifierLine() {
		return $this->getLineWithText(self::CLASS_QUALIFIER);
	}

	/**
	 * @return null|string
	 */
	public function getUsedTraitsLine() {
		return $this->getLineWithText(self::USED_TRAITS);
	}

	/**
	 * @param string $namespaceValue
	 */
	public function writeNamespace($namespaceValue) {
		$this->replaceTextInStub(ClassStubFile::DUMMY_NAMESPACE, $namespaceValue);
	}

	public function removeNamespace() {
		$this->replaceTextInStub($this->getNamespaceDeclarationLine(), "");
	}

	/**
	 * @param string $className
	 */
	public function writeClassName($className) {
		$this->replaceTextInStub(ClassStubFile::DUMMY_CLASSNAME, $className);
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeVariablesDeclarations($formattedContent) {
		$this->replaceTextInStub($this->getVariablesDeclarationLine(), $formattedContent);
	}

	/**
	 * @param string $className
	 */
	public function writeExtendsClassName($className) {
		$this->replaceTextInStub(ClassStubFile::EXTENDS_CLASSNAME, $className);
	}

	/**
	 * @param string $classQualifier
	 */
	public function writeClassQualifier($classQualifier) {
		$this->replaceTextInStub(ClassStubFile::CLASS_QUALIFIER, $classQualifier);
	}

	/**
	 * @param string[] $usedTraits
	 */
	public function writeUsedTraits($usedTraits) {
		$usedTraitsJoined = implode(", ", $usedTraits);
		$this->replaceTextInStub(ClassStubFile::USED_TRAITS, ($usedTraitsJoined ? "use $usedTraitsJoined;" : ""));
	}
}