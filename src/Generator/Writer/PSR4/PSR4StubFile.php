<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\StubFile;

class PSR4StubFile extends StubFile {
	const DUMMY_CLASSNAME = "DummyClass";
	const DUMMY_NAMESPACE = "DummyNamespace";
	const VARIABLES_DECLARATION = "'VariablesDeclarations';";

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
		if (empty($namespaceValue)) {
			$this->replaceTextInStub($this->getNamespaceDeclarationLine(), "");
		} else {
			$this->replaceTextInStub(PSR4StubFile::DUMMY_NAMESPACE, $namespaceValue);
		}
	}

	/**
	 * @param string $className
	 */
	public function writeClassName($className) {
		$this->replaceTextInStub(PSR4StubFile::DUMMY_CLASSNAME, $className);
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeVariablesDeclarations($formattedContent) {
		$this->replaceTextInStub(PSR4StubFile::VARIABLES_DECLARATION, $formattedContent);
	}

}