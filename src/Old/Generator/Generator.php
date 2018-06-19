<?php


namespace GraphQLGen\Old\Generator;


use GraphQL\Language\AST\DefinitionNode;
use GraphQL\Language\AST\EnumTypeDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQL\Language\AST\SchemaDefinitionNode;
use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Old\Generator\InterpretedTypes\InterpretedTypesStore;
use GraphQLGen\Old\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Old\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Old\Generator\Interpreters\InterfaceInterpreter;
use GraphQLGen\Old\Generator\Interpreters\Interpreter;
use GraphQLGen\Old\Generator\Interpreters\MainTypeInterpreter;
use GraphQLGen\Old\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Old\Generator\Interpreters\TypeDeclarationInterpreter;
use GraphQLGen\Old\Generator\Types\BaseTypeGenerator;
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
	 * @var InterpretedTypesStore
	 */
	protected $_interpretedTypesStore;

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
			if (!$astDefinition instanceof SchemaDefinitionNode) {
				$interpretedType = $this->getInterpretedTypeFromAST($astDefinition);
				$this->_interpretedTypesStore->registerInterpretedType($interpretedType);
				$this->logger->info("Acknowledged type {$interpretedType->getName()}");
			}
		}

		foreach ($this->_interpretedTypesStore->getInterpretedTypes() as $interpretedType) {
			$this->generateClass($interpretedType);
		}

		$this->finalizeClassesGeneration();
	}

	protected function initializeClassesGeneration() {
		// Initialize classes generation
		$this->logger->info("Initializing entries generation");
		$this->_context->writer->initialize();
		$this->_interpretedTypesStore = $this->_factory->createInterpretedTypesStore();
		$this->_context->formatter->setInterpretedTypesStore($this->_interpretedTypesStore);
	}

	/**
	 * @param DefinitionNode $astDefinition
	 * @return NamedTypeTrait|null
	 */
	protected function getInterpretedTypeFromAST($astDefinition) {
		// Interpret AST definition
		$interpreter = $this->_factory->getCorrectInterpreter($astDefinition);

		if(!is_null($interpreter)) {
			return $interpreter->generateType();
		}

		return null;
	}

	/**
	 * @param NamedTypeTrait $interpretedType
	 */
	protected function generateClass($interpretedType) {
		$fragmentGenerator = $this->_factory->createFragmentGenerator($this->_context->formatter, $interpretedType);
		$this->logger->info("Generating class for {$fragmentGenerator->getName()}");
		$this->generateClassFromType($fragmentGenerator);
	}

	protected function finalizeClassesGeneration() {
		// Finalize classes generation
		$this->logger->info("Finalizing entries generation");
		$this->_context->writer->finalize();
	}

	/**
	 * @param mixed $typeGenerator
	 */
	protected function generateClassFromType($typeGenerator) {
		$this->_context->writer->generateFileForTypeGenerator($typeGenerator);
	}
}