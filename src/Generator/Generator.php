<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\DefinitionNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\InterfaceInterpreter;
use GraphQLGen\Generator\Interpreters\Interpreter;
use GraphQLGen\Generator\Interpreters\MainTypeInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Generator\Interpreters\TypeDeclarationInterpreter;
use GraphQLGen\Generator\Types\BaseTypeGenerator;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Generator implements LoggerAwareInterface {
	use LoggerAwareTrait;

	/**
	 * @var GeneratorContext
	 */
	protected $_context;

	/**
	 * @var GeneratorFactory
	 */
	protected $_factory;

	/**
	 * @param GeneratorContext $context
	 * @param GeneratorFactory $factory
	 */
	public function __construct($context, $factory = null) {
		$this->_context = $context;
		$this->_factory = $factory ?: new GeneratorFactory();
	}

	public function generateClasses() {
		$this->initializeClassesGeneration();

		foreach($this->_context->ast->definitions as $astDefinition) {
			$this->interpretASTDefinition($astDefinition);
		}

		$this->finalizeClassesGeneration();
	}

	protected function initializeClassesGeneration() {
		// Initialize classes generation
		$this->logger->info("Initializing entries generation");
		$this->_context->writer->initialize();
	}

	/**
	 * @param DefinitionNode $astDefinition
	 */
	protected function interpretASTDefinition($astDefinition) {
		// Interpret AST definition
		$interpreter = $this->_factory->getCorrectInterpreter($astDefinition);

		if(!is_null($interpreter)) {
			$generatorType = $interpreter->generateType($this->_context->formatter);
			$this->logger->info("Generating entry for {$generatorType->getName()}");
			$this->generateClassFromType($generatorType);
		}
	}

	protected function finalizeClassesGeneration() {
		// Finalize classes generation
		$this->logger->info("Finalizing entries generation");
		$this->_context->writer->finalize();
	}

	/**
	 * @param BaseTypeGenerator $typeGenerator
	 */
	protected function generateClassFromType($typeGenerator) {
		$this->_context->writer->generateFileForTypeGenerator($typeGenerator);
	}
}