<?php


namespace GraphQLGen\Commands;


use GraphQL\Language\Parser;
use GraphQLGen\Generator\Generator;
use GraphQLGen\Generator\GeneratorContext;
use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Writer\PSR4Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateClassesCommand extends Command {
    /**
     * @var OutputInterface
     */
    protected $_output;

    protected function configure() {
        $this
            ->setName("generate-classes")
            ->setDescription("Generates PHP classes for a GraphQL schema.")
            ->setHelp("Generates PHP classes for a GraphQL schema.")

            ->addArgument("input", InputArgument::REQUIRED, "Input GraphQL file")
            ->addArgument("output", InputArgument::REQUIRED, "PHP classes output directory");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->_output = $output;

        // Read GraphQL file content
        $graphqlFileContent = file_get_contents($input->getArgument("input"));

        // Generate AST from GraphQL file
        $ast = Parser::parse($graphqlFileContent);

        // Create context
        $genContext = new GeneratorContext();
        $genContext->ast = $ast;
        $genContext->namespace = 'Test';
        $genContext->formatter = new StubFormatter();
        $genContext->writer = new PSR4Writer(
            $input->getArgument('output'),
            $genContext->namespace,
            true
        );

        // Starts work on AST
        $generator = new Generator($genContext);
        $generator->generateClasses();
    }
}