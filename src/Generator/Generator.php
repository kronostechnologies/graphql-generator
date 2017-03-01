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
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Generator implements LoggerAwareInterface {
	use LoggerAwareTrait;

	/**
	 * @var GeneratorContext
	 */
	protected $_context;

	/**
	 * @param GeneratorContext $context
	 */
	public function __construct($context) {
		$this->_context = $context;
	}

	public function generateClasses() {
		$this->logger->info("Initializing entries generation");
		$this->_context->writer->initialize();

		foreach($this->_context->ast->definitions as $astDefinition) {
			$interpreter = $this->getCorrectInterpreter($astDefinition);

			if(!is_null($interpreter)) {
				$generatorType = $interpreter->generateType($this->_context->formatter);
				$this->logger->info("Generating entry for {$generatorType->getName()}");
				$this->generateClassFromType($generatorType);
			}
		}

		$this->logger->info("Finalizing entries generation");
		$this->_context->writer->finalize();
	}

	/**
	 * @param DefinitionNode|ScalarTypeDefinitionNode|EnumTypeDefinitionNode|ObjectTypeDefinitionNode|InterfaceTypeDefinitionNode $astDefinitionNode
	 * @return MainTypeInterpreter
	 */
	protected function getCorrectInterpreter($astDefinitionNode) {
		switch($astDefinitionNode->kind) {
			case NodeKind::SCALAR_TYPE_DEFINITION:
				return new ScalarInterpreter($astDefinitionNode);
			case NodeKind::ENUM_TYPE_DEFINITION:
				return new EnumInterpreter($astDefinitionNode);
			case NodeKind::OBJECT_TYPE_DEFINITION:
				return new TypeDeclarationInterpreter($astDefinitionNode);
			case NodeKind::INTERFACE_TYPE_DEFINITION:
				return new InterfaceInterpreter($astDefinitionNode);
		}

		return null;
	}

	/**
	 * @param BaseTypeGeneratorInterface $typeGenerator
	 */
	protected function generateClassFromType($typeGenerator) {
		$this->_context->writer->generateFileForTypeGenerator($typeGenerator);
	}
}