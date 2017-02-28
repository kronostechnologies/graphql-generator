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
		// Init stub
		$stubFile = new PSR4StubFile();
		$stubFile->loadFromFile(__DIR__ . '/stubs/typestore.stub');

		// Replaces namespace
		$stubFile->replaceTextInStub(
			PSR4StubFile::DUMMY_NAMESPACE,
			$this->_context->resolver->joinAndStandardizeNamespaces($this->_context->namespace)
		);

		// Writes file
		$fullPath = $this->_context->getFilePath("TypeStore.php");
		if(file_exists($fullPath) && $this->_context->overwriteOldFiles) {
			unlink($fullPath);
		}
		file_put_contents($fullPath, $stubFile->getContent());

		$this->replaceResolvedTokens();
	}

	public function replaceResolvedTokens() {
		foreach($this->_context->resolver->getAllResolvedTokens() as $token => $fqn) {
			// Strips namespace end
			$filePath = $this->_context->getFilePath(
				$this->_context->resolver->getFilePathSuffixForFQN($fqn)
			);

			// Read file
			if(file_exists($filePath)) {
				$fileContent = file_get_contents($filePath);

				foreach($this->_context->resolver->getAllResolvedTokens() as $token2 => $fqn2) {
					$fileContent = str_replace($token2, "use " . $fqn2 . ";", $fileContent);
				}

				// rewrite file
				unlink($filePath);
				file_put_contents($filePath, $fileContent);
			}
		}
	}
}