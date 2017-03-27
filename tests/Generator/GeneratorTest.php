<?php


namespace GraphQLGen\Tests\Generator;


use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\ScalarTypeDefinitionNode;
use GraphQLGen\Generator\Generator;
use GraphQLGen\Generator\GeneratorContext;
use GraphQLGen\Generator\GeneratorFactory;
use GraphQLGen\Generator\GeneratorLogger;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\Interpreters\Main\ScalarInterpreter;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Writer\PSR4\PSR4Writer;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerAwareInterface;

class GeneratorTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var GeneratorFactory|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_factory;

	/**
	 * @var GeneratorContext|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_context;

	/**
	 * @var Generator
	 */
	protected $_generator;

	/**
	 * @var GeneratorLogger|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_logger;

	/**
	 * @var PSR4Writer|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_writer;

	/**
	 * @var ScalarInterpreter|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $_interpreterMock;

	public function setUp() {
		$this->_factory = $this->createMock(GeneratorFactory::class);

		$this->_writer = $this->createMock(PSR4Writer::class);

		$this->_context = $this->createMock(GeneratorContext::class);
		$this->_context->writer = $this->_writer;
		$this->_context->ast = new DocumentNode([]);
		$this->_context->ast->definitions = [];

		$this->_logger = $this->createMock(GeneratorLogger::class);

		$this->_generator = new Generator($this->_context, $this->_factory);
		$this->_generator->setLogger($this->_logger);

		$this->_interpreterMock = $this->createMock(ScalarInterpreter::class);
		$this->_interpreterMock->method('generateType')->willReturn($this->createMock(ScalarInterpretedType::class));
	}

	public function test_GivenContext_generateClasses_WillLog() {
		$this->_logger->expects($this->any())->method('info');

		$this->_generator->generateClasses();
	}

	public function test_GivenContext_generateClasses_WillInitializeWriter() {
		$this->_writer->expects($this->once())->method('initialize');

		$this->_generator->generateClasses();
	}

	public function test_GivenContextWithNoASTDefinition_generateClasses_WontGetInterpreter() {
		$this->_factory->expects($this->never())->method('getCorrectInterpreter');

		$this->_generator->generateClasses();
	}

	public function test_GivenContextWithSingleASTDefinition_generateClasses_WillGetInterpreter() {
		$this->GivenSingleASTDefinition();

		$this->_factory->expects($this->once())->method('getCorrectInterpreter');

		$this->_generator->generateClasses();
	}

	public function test_GivenContextWithSingleASTDefinition_generateClasses_WillCallGenerateType() {
		$this->GivenSingleASTDefinition();
		$this->_factory->method('getCorrectInterpreter')->willReturn($this->_interpreterMock);

		$this->_interpreterMock->expects($this->once())->method('generateType');

		$this->_generator->generateClasses();
	}



	public function test_GivenContext_generateClasses_WillFinalizeWriter() {
		$this->_writer->expects($this->once())->method('finalize');

		$this->_generator->generateClasses();
	}

	protected function GivenSingleASTDefinition() {
		$this->_context->ast->definitions[] = new ScalarTypeDefinitionNode([]);
	}
}