<?php


namespace GraphQLGen;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

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
}