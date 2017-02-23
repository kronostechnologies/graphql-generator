<?php


namespace GraphQLGen\Commands;


use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Generator;
use GraphQLGen\Generator\GeneratorContext;
use GraphQLGen\Generator\Types\SubTypes\TypeFormatter;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;
use GraphQLGen\Generator\Writer\PSR4\PSR4TypeFormatter;
use GraphQLGen\Generator\Writer\PSR4\PSR4Writer;
use GraphQLGen\Generator\Writer\PSR4\PSR4WriterContext;
use GraphQLGen\Generator\Writer\WriterContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateClassesCommand extends Command {
	protected function configure() {
		$this
			->setName("generate-classes")
			->setDescription("Generates PHP classes for a GraphQL schema.")
			->setHelp("Generates PHP classes for a GraphQL schema.")
			->addArgument("input", InputArgument::REQUIRED, "Input GraphQL file")
			->addOption("writer", "w", InputArgument::OPTIONAL, "Optional. Classes used for generating the output files. [psr4] Default: psr4", "psr4")
			->addOption("formatter-use-tabs", "ft", null, "Optional. Output formatter will use tabs instead of spaces.")
			->addOption("formatter-indent-spaces", "fi", InputArgument::OPTIONAL, "Optional. If formatter isn't using tabs, number of spaces per indent block.", 4)
			->addOption("formatter-line-merge", "fm", InputArgument::OPTIONAL, "Optional. In case descriptions are splitted on multiple lines, specify the separator to use between each ones.", ",");

		foreach (self::getWriterMappings() as $mapping) {
			/** @var WriterContext $context */
			$context = new $mapping['context'];
			$context->configureCLI($this);
		}
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		// Writer name
		$writerName = $input->getOption('writer');

		// Writer context (only necessary class to pre-init)
		$writerContext = $this->generateWriterContext($writerName);
		$writerContext->executeCLI($input, $output);

		// Read GraphQL file content
		$graphqlFileContent = $this->readGraphqlFile($input->getArgument('input'));

		// Creates context
		$genContext = new GeneratorContext();
		$genContext->ast = $this->generateGraphqlAST($graphqlFileContent);
		$genContext->formatter = new StubFormatter(
			$input->getOption('formatter-use-tabs'),
			$input->getOption('formatter-indent-spaces'),
			$input->getOption('formatter-line-merge'),
			$this->generateWriterTypeFormatter($writerName)
		);
		$writerContext->formatter = $genContext->formatter;
		$genContext->writer = $this->generateWriter($writerName, $writerContext);

		// Launch work
		$generator = new Generator($genContext);
		$generator->generateClasses();
	}

	/**
	 * @param string $writerName
	 * @return WriterContext
	 */
	protected function generateWriterContext($writerName) {
		$writerMappings = self::getWriterMappings();
		$writerContext = new $writerMappings[$writerName]['context'];

		return $writerContext;
	}

	/**
	 * @param string $writerName
	 * @param WriterContext $writerContext
	 * @return GeneratorWriterInterface
	 */
	protected function generateWriter($writerName, $writerContext) {
		$writerMappings = self::getWriterMappings();

		return new $writerMappings[$writerName]['writer']($writerContext);
	}

	/**
	 * @param string $writerName
	 * @return TypeFormatter
	 */
	protected function generateWriterTypeFormatter($writerName) {
		$writerMappings = self::getWriterMappings();

		return new $writerMappings[$writerName]['type-formatter']();
	}

	/**
	 * @param string $fileName
	 * @return string
	 */
	protected function readGraphqlFile($fileName) {
		return file_get_contents($fileName);
	}

	/**
	 * @param string $content
	 * @return DocumentNode
	 */
	protected function generateGraphqlAST($content) {
		return Parser::parse($content);
	}

	/**
	 * @return string[][]
	 */
	public static function getWriterMappings() {
		return [
			'psr4' => [
				'writer' => PSR4Writer::class,
				'context' => PSR4WriterContext::class,
				'type-formatter' => PSR4TypeFormatter::class
			]
		];
	}
}