<?php


namespace GraphQLGen\Generator;


use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeKind;
use GraphQLGen\Generator\Interpreters\EnumInterpreter;
use GraphQLGen\Generator\Interpreters\FieldInterpreter;
use GraphQLGen\Generator\Interpreters\GeneratorInterpreterInterface;
use GraphQLGen\Generator\Interpreters\TypeInterpreter;
use GraphQLGen\Generator\Interpreters\ScalarInterpreter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

class Generator {

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
	 * @param GeneratorFactory|null $factory
	 */
	public function __construct($context, $factory = null) {
		$this->_context = $context;
		$this->_factory = $factory ?: new GeneratorFactory();
	}

	public function generateClasses() {
		$this->_context->writer->initialize();

		foreach($this->_context->ast->definitions as $astDefinition) {
			$interpreter = $this->getCorrectInterpreter($astDefinition);

			if(!is_null($interpreter)) {
				$generatorType = $this->getGeneratorTypeFromInterpreter($interpreter);
				$this->generateClassFromType($generatorType);
			}
		}
	}

	/**
	 * @param Node $astNode
	 * @return EnumInterpreter|TypeInterpreter|ScalarInterpreter|null
	 */
	protected function getCorrectInterpreter($astNode) {
		switch($astNode->kind) {
			case NodeKind::SCALAR_TYPE_DEFINITION:
				return $this->_factory->createScalarTypeInterpreter($astNode);
			case NodeKind::ENUM_TYPE_DEFINITION:
				return $this->_factory->createEnumTypeInterpreter($astNode);
			case NodeKind::OBJECT_TYPE_DEFINITION:
				return $this->_factory->createObjectTypeInterpreter($astNode);
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

	/**
	 * @param $interpreter
	 * @return Types\EnumType|Types\ObjectType|Types\ScalarType|Types\SubTypes\FieldType|null
	 */
	protected function getGeneratorTypeFromInterpreter($interpreter) {
		switch (get_class($interpreter)) {
			case EnumInterpreter::class:
				return $this->_factory->createEnumGeneratorType($interpreter, $this->_context->formatter);
			case FieldInterpreter::class:
				return $this->_factory->createFieldTypeGeneratorType($interpreter);
			case TypeInterpreter::class:
				return $this->_factory->createObjectGeneratorType($interpreter, $this->_context->formatter);
			case ScalarInterpreter::class:
				return $this->_factory->createScalarGeneratorType($interpreter, $this->_context->formatter);
		}

		return null;
	}
}