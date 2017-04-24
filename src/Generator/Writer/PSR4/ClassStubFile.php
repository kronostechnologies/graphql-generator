<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\StubFile;

/**
 * StubFile with special PSR-4 annotations.
 *
 * Class ClassStubFile
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class ClassStubFile extends StubFile {
	const DUMMY_CLASSNAME = "ClassName";
	const DUMMY_NAMESPACE = "LocalNamespace";
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
		if ($className === '') {
			$this->replaceTextInStub("extends " . ClassStubFile::EXTENDS_CLASSNAME, "");
		} else {
			$this->replaceTextInStub("extends " . ClassStubFile::EXTENDS_CLASSNAME, "extends " . $className);
		}
	}
}