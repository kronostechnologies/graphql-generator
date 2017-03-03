<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\StubFile;

class ClassStubFile extends StubFile {
	const DUMMY_CLASSNAME = "ClassName";
	const DUMMY_NAMESPACE = "LocalNamespace";
	const VARIABLES_DECLARATION = "'// @generate:Variables";

	/**
	 * @return null|string
	 */
	public function getNamespaceDeclarationLine() {
		return $this->getLineWithText(self::DUMMY_NAMESPACE);
	}

	/**
	 * @return null|string
	 */
	public function getDummyClassNameLine() {
		return $this->getLineWithText(self::DUMMY_CLASSNAME);
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
	public function writeOrStripNamespace($namespaceValue) {
		if(empty($namespaceValue)) {
			$this->replaceTextInStub($this->getNamespaceDeclarationLine(), "");
		}
		else {
			$this->replaceTextInStub(ClassStubFile::DUMMY_NAMESPACE, $namespaceValue);
		}
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
		$this->replaceTextInStub(ClassStubFile::VARIABLES_DECLARATION, $formattedContent);
	}

}