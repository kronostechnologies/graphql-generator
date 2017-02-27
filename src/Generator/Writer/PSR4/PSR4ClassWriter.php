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
	 */
	public function __construct($type, $context) {
		$this->_context = $context;
		$this->_type = $type;
	}

	public function loadStubFile() {
		$this->_stubFile = new PSR4StubFile();
		$this->_stubFile->loadFromFile(__DIR__ . $this->_context->resolver->getStubFilenameForType($this->_type));
	}

	public function loadPSR4Formatter() {
		$this->_psr4ClassFormatter = new PSR4ClassFormatter($this->_context->formatter, $this->_stubFile);
	}

	public function replacePlaceholdersAndWriteToFile() {
		$this->loadStubFile();
		$this->loadPSR4Formatter();
		$this->writeNamespace();
		$this->writeUsesTokens();
		$this->writeClassName();
		$this->writeVariablesDeclaration();
		$this->writeTypeDefinition();
		$this->writeClassToFile();
	}

	/**
	 * @return string
	 */
	public function getFormattedTypeDefinition() {
		$unformattedTypeDefinition = $this->_type->generateTypeDefinition();

		return $this->_psr4ClassFormatter->getFormattedTypeDefinition($unformattedTypeDefinition);
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
			$variablesDeclarationNoIndent = $this->_type->getConstantsDeclaration();

			return $this->_psr4ClassFormatter->getFormattedVariablesDeclaration($variablesDeclarationNoIndent);
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
	public function getUsesTokens() {
		$dependencies = $this->_type->getDependencies();

		return $this->_context->resolver->generateTokensFromDependencies($dependencies);
	}

	/**
	 * @return string
	 */
	public function getClassFilePath() {
		return $this->_context->getFilePath(
			$this->_context->resolver->getFilePathSuffixForFQN(
				$this->_context->resolver->getFQNForType($this->_type)
			)
		);
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
			implode("\n", $this->getUsesTokens())
		);
	}

	protected function writeClassToFile() {
		$fullPath = $this->getClassFilePath();

		if(file_exists($fullPath) && $this->_context->overwriteOldFiles) {
			unlink($fullPath);
		}

		file_put_contents($fullPath, $this->_stubFile->getContent());
	}

}