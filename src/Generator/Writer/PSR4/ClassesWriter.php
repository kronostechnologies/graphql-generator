<?php


namespace GraphQLGen\Generator\Writer\PSR4;

use GraphQLGen\Generator\Formatters\ClassFormatter;


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


	public function writeClasses() {
		foreach($this->_classMapper->getClasses() as $class) {
			$contentCreator = $class->getContentCreator();

			// Resolve dependencies
			$resolvedDependencies = $this->resolveDependenciesAsContent($class->getDependencies());

			$contentFormatter = new ClassFormatter();
			$contentFormatter->setBuffer($contentCreator->getContent());
			$contentFormatter->setUseSpaces($this->_writerContext->formatter->useSpaces);
			$contentFormatter->setTabSize($this->_writerContext->formatter->tabSize);
			$formattedContent = $contentFormatter->format(1);

			$stubFileName = ClassStubFile::getStubFilenameForClass($class);
			$destinationPath = $this->mapFQNToFilePath($class->getFullQualifiedName());
			$stubFileFullPath = $this->_writerContext->getStubFilePath($stubFileName);
			$stubContent = $this->_writerContext->readFileContentToString($stubFileFullPath);

			$stubFile = new ClassStubFile();
			$stubFile->setFileContent($stubContent);
			$stubFile->writeContent($formattedContent);
			$stubFile->writeClassName($contentCreator->getClassName());
			$stubFile->writeVariablesDeclarations($contentCreator->getVariables());
			$stubFile->writeDependenciesDeclaration($resolvedDependencies);
			$stubFile->writeParentClassName($contentCreator->getParentClassName());

			if (empty($contentCreator->getNamespace())) {
				$stubFile->removeNamespace();
			} else {
				$stubFile->writeNamespace($contentCreator->getNamespace()); // ToDo: Check for blank namespace
			}

			$this->_writerContext->makeFileDirectory($destinationPath);
			$this->_writerContext->writeFile($destinationPath, $stubFile->getFileContent());

			// ToDo: Save stub file

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

		return implode("\n", $resolvedDependenciesAsLines);
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
		$nonPrefixedFQN = substr($fqn, strlen($baseNSStandardized));
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