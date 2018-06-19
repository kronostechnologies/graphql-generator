<?php


namespace GraphQLGen;


use GraphQLGen\FileSystem\FileSystemHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MainCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName("generate-classes")
            ->setHelp("Generate PHP classes for the GraphQL framework from an input graphqls file.")
            ->addArgument("input", InputArgument::REQUIRED, "Input graphqls file")
            ->addArgument("output", InputArgument::REQUIRED, "Output directory");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new ClassesGeneratorConfig(
            $input->getArgument('input'),
            $input->getArgument('output')
        );

        $generator = new ClassesGenerator($config);
        $generator->execute();
    }
}