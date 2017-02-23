<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

class PSR4ClassWriter {
	/**
	 * @var PSR4Formatter
	 */
	public $psr4Formatter;

	/**
	 * @var PSR4Resolver
	 */
	public $psr4Resolver;

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

		$this->psr4Formatter = new PSR4Formatter($this->_context->formatter);
		$this->psr4Resolver = new PSR4Resolver($this->_context->namespace);
	}

	public function initializeStub() {
		$stub = file_get_contents(__DIR__ . $this->psr4Resolver->getStubFilenameForType($this->_type));
		$this->_stubFile = new PSR4StubFile($stub);
	}

	public function replaceTypeDefinitionDeclaration() {
		$typeDefinitionLine = $this->_stubFile->getTypeDefinitionDeclarationLine();
		$typeDefinitionUnformatted = $this->_type->generateTypeDefinition();
		$typeDefinitionFormatted = $this->psr4Formatter->formatTypeDefinition($typeDefinitionLine, $typeDefinitionUnformatted);
		$this->_stubFile->writeTypeDefinitionDeclaration($typeDefinitionFormatted);
	}

	public function replaceClassName() {
		$className = $this->_type->getName();
		$this->_stubFile->writeClassName($className);
	}

	public function replaceNamespace() {
		$namespace = $this->psr4Resolver->getFullNamespaceForType($this->_type);
		$noSlashNamespace = $this->psr4Resolver->removeNamespaceTrailingSlashes($namespace);
		$this->_stubFile->writeNamespace($noSlashNamespace);
	}

	public function replaceVariablesDeclaration() {
		if($this->_context->formatter->useConstantsForEnums) {
			$variablesDeclarationLine = $this->_stubFile->getVariablesDeclarationLine();
			$variablesDeclarationNoIndent = $this->_type->getConstantsDeclaration();
			$variablesDeclarationFormatted = $this->psr4Formatter->formatTypeDefinition($variablesDeclarationLine, $variablesDeclarationNoIndent);
			$this->_stubFile->writeVariablesDeclarations($variablesDeclarationFormatted);
		}
		else {
			$this->_stubFile->writeVariablesDeclarations("");
		}
	}

	public function replaceUsesDeclaration() {
		$usesDependencies = $this->_type->getDependencies();
		$usesDeclarations = $this->psr4Resolver->getAllNamespacesFromDependencies($usesDependencies);
		$this->_stubFile->writeUsesDeclaration($usesDeclarations);
	}

	public function writeClass() {
		$fullPath = $this->getClassFullPath();

		if (file_exists($fullPath) && $this->_context->overwriteOldFiles) {
			unlink($fullPath);
		}

		file_put_contents($fullPath, $this->_stubFile->getContent());
	}

	/**
	 * @return string
	 */
	public function getClassFullPath() {
		$classFQN = $this->psr4Resolver->getFQNForType($this->_type);

		$relevantFQN = substr($classFQN, strlen($this->_context->namespace));
		$relevantFQN = str_replace("\\", "/", $relevantFQN);

		return $this->_context->targetDir . $relevantFQN . ".php";
	}

}