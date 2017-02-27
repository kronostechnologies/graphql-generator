<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

class PSR4ClassWriter {
	/**
	 * @var PSR4ClassFormatter
	 */
	protected $_psr4ClassFormatter;

	/**
	 * @var PSR4WriterContext
	 */
	protected $_context;

	/**
	 * @var BaseTypeGeneratorInterface
	 */
	protected $_type;

	/**
	 * @var PSR4StubFile
	 */
	protected $_stubFile;

	/**
	 * PSR4ClassWriter constructor.
	 * @param BaseTypeGeneratorInterface $type
	 * @param PSR4WriterContext $context
	 * @param PSR4StubFile $stubFile
	 * @param PSR4ClassFormatter $psr4Formatter
	 */
	public function __construct($type, $context, $stubFile, $psr4Formatter) {
		$this->_context = $context;
		$this->_type = $type;
		$this->_psr4ClassFormatter = $psr4Formatter;
		$this->_stubFile = $stubFile;
	}

	public function replacePlaceholders() {
		$this->writeNamespace();
		$this->writeUsesTokens();
		$this->writeClassName();
		$this->writeVariablesDeclaration();
		$this->writeTypeDefinition();
	}

	/**
	 * @return string
	 */
	public function getFormattedTypeDefinition() {
		$stubTypeDefinitionLine = $this->_stubFile->getTypeDefinitionDeclarationLine();
		$unformattedTypeDefinition = $this->_type->generateTypeDefinition();

		return $this->_psr4ClassFormatter->getFormattedTypeDefinition($stubTypeDefinitionLine, $unformattedTypeDefinition);
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->_type->getName();
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarationFormatted() {
		if($this->_context->formatter->useConstantsForEnums) {
			$stubVariableDeclarationLine = $this->_stubFile->getVariablesDeclarationLine();
			$variablesDeclarationNoIndent = $this->_type->getConstantsDeclaration();

			return $this->_psr4ClassFormatter->getFormattedVariablesDeclaration($stubVariableDeclarationLine, $variablesDeclarationNoIndent);
		}
		else {
			return "";
		}
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->_context->resolver->getNamespaceForType($this->_type);
	}

	/**
	 * @return string[]
	 */
	public function getImportedNamespacesTokens() {
		$dependencies = $this->_type->getDependencies();

		return $this->_context->resolver->generateTokensFromDependencies($dependencies);
	}

	/**
	 * @return string
	 */
	public function getFilePath() {
		return $this->_context->getFilePath(
			$this->_context->resolver->getFilePathSuffixForFQN(
				$this->_context->resolver->getFQNForType($this->_type)
			)
		);
	}

	/**
	 * @return string
	 */
	public function getClassContent() {
		return $this->_stubFile->getContent();
	}

	protected function writeTypeDefinition() {
		$this->_stubFile->writeTypeDefinition(
			$this->getFormattedTypeDefinition()
		);
	}

	protected function writeClassName() {
		$this->_stubFile->writeClassName(
			$this->getClassName()
		);
	}

	protected function writeNamespace() {
		$this->_stubFile->writeNamespace(
			$this->getNamespace()
		);
	}

	protected function writeVariablesDeclaration() {
		$this->_stubFile->writeVariablesDeclarations($this->getVariablesDeclarationFormatted());
	}

	protected function writeUsesTokens() {
		$this->_stubFile->writeUsesDeclaration(
			implode("\n", $this->getImportedNamespacesTokens())
		);
	}
}