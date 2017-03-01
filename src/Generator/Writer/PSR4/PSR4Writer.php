<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\GeneratorContext;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;
use GraphQLGen\Generator\Writer\StubFile;

class PSR4Writer implements GeneratorWriterInterface {
	/**
	 * @var PSR4WriterContext
	 */
	protected $_context;

	/**
	 * PSR4Writer constructor.
	 * @param PSR4WriterContext $context
	 */
	public function __construct(PSR4WriterContext $context) {
		$this->_context = $context;
	}

	public function initialize() {
		foreach(PSR4Resolver::getBasicPSR4Structure() as $structureFolder) {
			$this->_context->createDirectoryIfNonExistant(
				$this->_context->getFilePath($structureFolder)
			);
		}
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string|void
	 */
	public function generateFileForTypeGenerator($type) {
		$psr4Formatter = new PSR4ClassFormatter($this->_context->formatter);

		$stubFile = new PSR4StubFile();
		$stubFile->loadFromFile(__DIR__ . $this->_context->resolver->getStubFilenameForType($type));

		$classWriter = new PSR4ClassWriter($type, $this->_context, $stubFile, $psr4Formatter);

		// Store token for later usage
		$this->_context->resolver->storeFQNTokenForType($type);

		// Writes class from stub file
		$classWriter->replacePlaceholders();
		$fullPath = $classWriter->getFilePath();
		$content = $classWriter->getClassContent();
		$this->_context->writeFile($fullPath, $content);
	}

	public function finalize() {
		$stubFile = $this->getTypeStoreStub();

		$this->writeTypeStoreNamespace($stubFile);
		$this->writeTypeStoreFromStub($stubFile);
		$this->replaceResolvedTokens();
	}

	/**
	 * @param PSR4StubFile $stubFile
	 */
	protected function writeTypeStoreFromStub($stubFile) {
		$fullPath = $this->_context->getFilePath("TypeStore.php");
		$typeStoreContent = $stubFile->getContent();
		$this->_context->writeFile($fullPath, $typeStoreContent);
	}

	/**
	 * @param PSR4StubFile $stubFile
	 */
	protected function writeTypeStoreNamespace($stubFile) {
		$stubFile->replaceTextInStub(
			PSR4StubFile::DUMMY_NAMESPACE,
			$this->_context->resolver->joinAndStandardizeNamespaces($this->_context->namespace)
		);
	}

	/**
	 * @return PSR4StubFile
	 */
	protected function getTypeStoreStub() {
		$stubFile = new PSR4StubFile();
		$stubFile->loadFromFile(__DIR__ . '/stubs/typestore.stub');

		return $stubFile;
	}

	protected function replaceResolvedTokens() {
		foreach($this->_context->resolver->getAllResolvedTokens() as $token => $fqn) {
			$this->resolveResolvedTokensForSpecificFQN($token, $fqn);
		}
	}

	protected function resolveResolvedTokensForSpecificFQN($token, $fqn) {
		$filePath = $this->getFilePathForFQN($fqn);

		if ($this->_context->fileExists($filePath)) {
			$fileContent = $this->_context->readFileContentToString($filePath);
			$fileContent = $this->replaceResolvedTokensInString($fileContent);

			$this->_context->writeFile($filePath, $fileContent, true);
		}
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function replaceResolvedTokensInString($string) {
		foreach($this->_context->resolver->getAllResolvedTokens() as $token => $fqn) {
			$string = str_replace($token, "use " . $fqn . ";", $string);
		}

		return $string;
	}

	/**
	 * @param string $fqn
	 * @return string
	 */
	protected function getFilePathForFQN($fqn) {
		return $this->_context->getFilePath(
			$this->_context->resolver->getFilePathSuffixForFQN($fqn)
		);
	}
}