<?php


namespace GraphQLGen;


use GraphQLGen\FileSystem\FileSystemHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MainCommand extends Command
{
    /**
     * @var FileSystemHelper
     */
    protected $fileSystemHelper;

    public function __construct(FileSystemHelper $fileSystemHelper = null)
    {
        parent::__construct();

        $this->fileSystemHelper = $fileSystemHelper;
    }

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
        $inputFileName = $input->getArgument("input");
        $outputDirectory = $input->getArgument("output");
    }
}