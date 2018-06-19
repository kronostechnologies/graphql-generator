<?php


namespace GraphQLGen;


class ClassesGeneratorConfig
{
    /**
     * @var string
     */
    protected $inputFileName;

    /**
     * @var string
     */
    protected $outputDirectory;

    /**
     * @param string $inputFileName
     * @param string $outputDirectory
     */
    public function __construct($inputFileName, $outputDirectory)
    {
        $this->inputFileName = $inputFileName;
        $this->outputDirectory = $outputDirectory;
    }

    /**
     * @return string
     */
    public function getInputFileName()
    {
        return $this->inputFileName;
    }

    /**
     * @return string
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }
}