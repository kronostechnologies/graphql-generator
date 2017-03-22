<?php


namespace GraphQLGen\Generator\Writer\PSR4;

use GraphQLGen\Generator\Formatters\ClassFormatter;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;


/**
 * Takes care of writing classes to stub files, then to disk through the context. Also resolves PSR-4 namespaces to filenames.
 *
 * Class ClassesWriter
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class ClassesWriter {
	/**
	 * @var ClassMapper
	 */
	protected $_classMapper;

	/**
	 * @var PSR4WriterContext
	 */
	protected $_writerContext;

	/**
	 * @var ClassesFactory
	 */
	protected $_factory;

	public function __construct($factory = null) {
		$this->_factory = $factory ?: new ClassesFactory();
	}

	public function writeClasses() {
		foreach($this->_classMapper->getClasses() as $class) {
			$this->writeIndividualClass($class);
		}
	}

	/**
	 * @param SingleClass $class
	 */
	public function writeIndividualClass(SingleClass $class) {
		$contentCreator = $class->getContentCreator();
		$classFormatter = $this->_writerContext->getConfiguredClassFormatter();

		$stubFileDirectory = $this->fetchStubFileDirectory($class);
		$stubFilePath = $this->fetchStubFilePath($class);

		$stubFile = $this->createClassStubFileFromPath($stubFilePath);

		$this->writeContent($contentCreator, $classFormatter, $stubFile);
		$this->writeVariables($contentCreator, $classFormatter, $stubFile);
		$this->writeDependencies($class, $stubFile);
		$this->writeNamespace($contentCreator, $stubFile);

		$stubFile->writeClassName($contentCreator->getClassName());
		$stubFile->writeExtendsClassName($contentCreator->getParentClassName());

		$this->_writerContext->makeFileDirectory($stubFileDirectory);
		$this->_writerContext->writeFile($stubFileDirectory, $stubFile->getFileContent());
	}

	/**
	 * @param SingleClass $class
	 * @return string
	 */
	public function fetchStubFileDirectory(SingleClass $class) {
		return $this->mapFQNToFilePath($class->getFullQualifiedName());
	}

	/**
	 * @param SingleClass $class
	 * @return string
	 */
	public function fetchStubFilePath(SingleClass $class) {
		$stubFileName = $class->getStubFileName();

		return $this->_writerContext->getStubFilePath($stubFileName);
	}

	/**
	 * @param $stubFilePath
	 * @return ClassStubFile
	 */
	protected function createClassStubFileFromPath($stubFilePath) {
		$stubContent = $this->_writerContext->readFileContentToString($stubFilePath);
		$stubFile = $this->_factory->createStubFile();
		$stubFile->setFileContent($stubContent);

		return $stubFile;
	}

	/**
	 * @param BaseContentCreator $contentCreator
	 * @param ClassFormatter $formatter
	 * @param ClassStubFile $stubFile
	 */
	protected function writeContent(BaseContentCreator $contentCreator, ClassFormatter $formatter, ClassStubFile $stubFile) {
		$formattedContent = $formatter->format($contentCreator->getContent(), 1);
		$stubFile->writeContent($formattedContent);
	}

	/**
	 * @param BaseContentCreator $contentCreator
	 * @param ClassFormatter $formatter
	 * @param ClassStubFile $stubFile
	 */
	protected function writeVariables(BaseContentCreator $contentCreator, ClassFormatter $formatter, ClassStubFile $stubFile) {
		$formattedVariables = $formatter->format($contentCreator->getVariables(), 1);
		$stubFile->writeVariablesDeclarations($formattedVariables);
	}

	/**
	 * @param SingleClass $class
	 * @param ClassStubFile $stubFile
	 */
	protected function writeDependencies(SingleClass $class, ClassStubFile $stubFile) {
		$resolvedDependencies = $this->resolveDependenciesAsContent($class->getDependencies());
		$stubFile->writeDependenciesDeclaration($resolvedDependencies);
	}

	/**
	 * @param BaseContentCreator $contentCreator
	 * @param ClassStubFile $stubFile
	 */
	protected function writeNamespace(BaseContentCreator $contentCreator, ClassStubFile $stubFile) {
		if(empty($contentCreator->getNamespace())) {
			$stubFile->removeNamespace();
		}
		else {
			$stubFile->writeNamespace($contentCreator->getNamespace());
		}
	}

	/**
	 * @return PSR4WriterContext
	 */
	public function getWriterContext() {
		return $this->_writerContext;
	}

	/**
	 * @param PSR4WriterContext $writerContext
	 */
	public function setWriterContext($writerContext) {
		$this->_writerContext = $writerContext;
	}

	/**
	 * @param string[] $dependencies
	 * @return string
	 */
	protected function resolveDependenciesAsContent($dependencies) {
		$resolvedDependenciesAsLines = [];

		foreach($dependencies as $dependency) {
			$resolvedDependenciesAsLines[] = "use " . $this->getClassMapper()->getResolvedDependency($dependency) . ";";
		}
		$resolvedDependenciesAsLines = array_unique($resolvedDependenciesAsLines);

		return implode(PHP_EOL, $resolvedDependenciesAsLines);
	}

	/**
	 * @param string $fqn
	 * @return string
	 */
	protected function mapFQNToFilePath($fqn) {
		// Standardize FQN
		$standardizedFQN = PSR4Utils::joinAndStandardizeNamespaces($fqn);

		// Removes base namespace part
		$baseNSStandardized = $this->getWriterContext()->namespace;
		$baseNSStandardized = trim($baseNSStandardized, "\\");
		$nonPrefixedFQN = substr($standardizedFQN, strlen($baseNSStandardized));
		$nonPrefixedFQN = ltrim($nonPrefixedFQN, "\\");

		// Appends .php
		$filePathFQN = $nonPrefixedFQN . ".php";
		$filePathFQN = str_replace("\\", "/", $filePathFQN);

		// Prefix path (no suffix slashes)
		$prefixPath = rtrim($this->_writerContext->targetDir, " \\/");

		return $prefixPath . "/" . $filePathFQN;
	}

	/**
	 * @return ClassMapper
	 */
	public function getClassMapper() {
		return $this->_classMapper;
	}

	/**
	 * @param ClassMapper $classMapper
	 */
	public function setClassMapper($classMapper) {
		$this->_classMapper = $classMapper;
	}
}