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

		$classWriter->initializeStub();
		$classWriter->replaceTypeDefinitionDeclaration();
		$classWriter->replaceClassName();
		$classWriter->replaceNamespace();
		$classWriter->replaceVariablesDeclaration();
		$classWriter->replaceUsesDeclaration();
		$classWriter->writeClass();
	}
}