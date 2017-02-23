<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\GeneratorContext;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;

class PSR4Writer implements GeneratorWriterInterface {
	/**
	 * @var PSR4WriterContext
	 */
	protected $_context;

	/**
	 * PSR4Writer constructor.
	 * @param GeneratorContext $context
	 */
	public function __construct($context) {
		$this->_context = $context;
	}

	public function initialize() {
		foreach(PSR4Resolver::getBasicPSR4Structure() as $structureFolder) {
			mkdir(getcwd() . '/' . $this->_context->targetDir . $structureFolder);
		}
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string|void
	 */
	public function generateFileForTypeGenerator($type) {
		$classWriter = new PSR4ClassWriter($type, $this->_context);

		$classWriter->replacePlaceholdersAndWriteToFile();
	}

	public function finalize() {
		// Init stub
		$stubFile = new PSR4StubFile();
		$stubFile->loadFromFile(__DIR__ . '/stubs/typestore.stub');

		// Replaces namespace
		$stubFile->replaceTextInStub(PSR4StubFile::DUMMY_NAMESPACE, $this->_context->namespace);

		// Writes file
		$fullPath = $this->_context->targetDir . 'TypeStore.php';
		if (file_exists($fullPath) && $this->_context->overwriteOldFiles) {
			unlink($fullPath);
		}
		file_put_contents($fullPath, $stubFile->getContent());

		$this->replaceResolvedTokens();
	}

	public function replaceResolvedTokens() {
		foreach ($this->_context->resolver->getAllResolvedTokens() as $token => $fqn) {
			// Strips namespace end
			$filePath = $this->_context->targetDir . $this->_context->resolver->getNamespaceDirectory($fqn) . ".php";
			$filePath = str_replace("\\", "/", $filePath);
			// Read file
			if (file_exists($filePath)) {
				$fileContent = file_get_contents($filePath);

				foreach ($this->_context->resolver->getAllResolvedTokens() as $token2 => $fqn2) {
					$fileContent = str_replace($token2, "use " . $fqn2 . ";", $fileContent);
				}

				// rewrite file
				unlink($filePath);
				file_put_contents($filePath, $fileContent);
			}
		}
	}
}