<?php


namespace GraphQLGen;


use GraphQLGen\Exception\InvalidInputFileException;
use GraphQLGen\Exception\InvalidOutputDirException;
use GraphQLGen\FileSystem\FileSystemHelper;

class ClassesGenerator
{
    /**
     * @var ClassesGeneratorConfig
     */
    protected $config;

    /**
     * @var FileSystemHelper
     */
    protected $fileSystemHelper;

    /**
     * @param ClassesGeneratorConfig $config
     * @param FileSystemHelper|null $fileSystemHelper
     */
    public function __construct(ClassesGeneratorConfig $config, FileSystemHelper $fileSystemHelper = null)
    {
        $this->config = $config;
        $this->fileSystemHelper = $fileSystemHelper ?: new FileSystemHelper();
    }

    /**
     * @throws InvalidInputFileException
     * @throws InvalidOutputDirException
     */
    public function execute()
    {
        $this->prechecks();

        $fileContent = $this->fileSystemHelper->getFileContent($this->config->getInputFileName());
        if ($fileContent === null) {
            throw new InvalidInputFileException();
        }
    }

    /**
     * @throws InvalidInputFileException
     * @throws InvalidOutputDirException
     */
    protected function prechecks()
    {
        if (!$this->fileSystemHelper->isFileReadable($this->config->getInputFileName()))
        {
            throw new InvalidInputFileException();
        }

        if (!$this->fileSystemHelper->isDirectoryWritable($this->config->getOutputDirectory()))
        {
            throw new InvalidOutputDirException();
        }
    }
}