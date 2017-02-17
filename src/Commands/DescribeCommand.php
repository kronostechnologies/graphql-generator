<?php


namespace GraphQLGen\Commands;


use GraphQL\Language\Parser;
use GraphQLGen\Schema\Descriptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DescribeCommand extends Command {
	protected function configure() {
		$this
			->setName("describe")
			->setDescription("Describes a GraphQL schema in console.")
			->setHelp("Describes a GraphQL schema in the console, highlighting potential problems.")
			->addOption("no-desc", null, InputOption::VALUE_NONE, "Hide descriptions.")
			->addArgument("input", InputArgument::REQUIRED, "Input GraphQL file");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		// Read GraphQL file content
		$graphqlFileContent = file_get_contents($input->getArgument("input"));

		// Generate AST from GraphQL file
		$ast = Parser::parse($graphqlFileContent);

		$descriptor = new Descriptor($output, !$input->getOption("no-desc"));
		$descriptor->describeAST($ast);
	}
}