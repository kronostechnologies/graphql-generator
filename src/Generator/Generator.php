<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\DefinitionNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\InterfaceInterpreter;
use GraphQLGen\Generator\Interpreters\Interpreter;
use GraphQLGen\Generator\Interpreters\TypeDeclarationInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

class Generator {

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
		$this->_context->writer->initialize();

		foreach($this->_context->ast->definitions as $astDefinition) {
			$interpreter = $this->getCorrectInterpreter($astDefinition);

			if(!is_null($interpreter)) {
				$generatorType = $interpreter->generateType($this->_context->formatter);
				$this->generateClassFromType($generatorType);
			}
		}
	}

	/**
	 * @param Node|DefinitionNode $astNode
	 * @return Interpreter
	 */
	protected function getCorrectInterpreter($astNode) {
		switch($astNode->kind) {
			case NodeKind::SCALAR_TYPE_DEFINITION:
				return new ScalarInterpreter($astNode);
			case NodeKind::ENUM_TYPE_DEFINITION:
				return new EnumInterpreter($astNode);
			case NodeKind::OBJECT_TYPE_DEFINITION:
				return new TypeDeclarationInterpreter($astNode);
			case NodeKind::INTERFACE_TYPE_DEFINITION:
				return new InterfaceInterpreter($astNode);
		}

		return null;
	}

	/**
	 * @param string $suffix
	 * @return string
	 */
	protected function getFullNamespace($suffix = '') {
		return $this->_context->namespace . '\\' . $suffix;
	}

	/**
	 * @param BaseTypeGeneratorInterface $typeGenerator
	 */
	protected function generateClassFromType($typeGenerator) {
		$this->_context->writer->generateFileForTypeGenerator($typeGenerator);
	}
}