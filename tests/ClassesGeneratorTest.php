<?php


namespace GraphQLGen\Tests;


use GraphQLGen\ClassesGenerator;
use GraphQLGen\ClassesGeneratorConfig;
use GraphQLGen\Exception\InvalidInputFileException;
use GraphQLGen\Exception\InvalidOutputDirException;
use GraphQLGen\FileSystem\FileSystemHelper;
use PHPUnit\Framework\TestCase;

class ClassesGeneratorTest extends TestCase
{
    const PROVIDED_INPUT_FILE = '/tmp/aninputfile.graphqls';
    const PROVIDED_INPUT_FILE_CONTENT = 'type Test { id: number }';
    const PROVIDED_OUTPUT_DIR = '/tmp/outputdir';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FileSystemHelper
     */
    protected $fileSystemHelper;


    /**
     * @var ClassesGenerator
     */
    protected $classesGenerator;

    public function setUp()
    {
        $this->fileSystemHelper = $this->getMockBuilder(FileSystemHelper::class)->getMock();

        $this->classesGenerator = new ClassesGenerator(
            new ClassesGeneratorConfig(
                self::PROVIDED_INPUT_FILE,
                self::PROVIDED_OUTPUT_DIR
            ), $this->fileSystemHelper
        );
    }

    protected function givenRemainingFileSystemChecksSucceed()
    {
        $this->fileSystemHelper->method('isFileReadable')->willReturn(true);
        $this->fileSystemHelper->method('isDirectoryWritable')->willReturn(true);
        $this->fileSystemHelper->method('getFileContent')->willReturn(self::PROVIDED_INPUT_FILE_CONTENT);
    }

    public function test_NonReadableInputFile_execute_ThrowsInvalidInputFileException()
    {
        $this->expectException(InvalidInputFileException::class);

        $this->fileSystemHelper->method('isFileReadable')->willReturn(false);

        $this->classesGenerator->execute();
    }

    public function test_NonWritableOutputDir_execute_ThrowsInvalidOutputDirException()
    {
        $this->expectException(InvalidOutputDirException::class);

        $this->fileSystemHelper->method('isDirectoryWritable')->willReturn(false);

        $this->givenRemainingFileSystemChecksSucceed();

        $this->classesGenerator->execute();
    }

    public function test_ReadFileFailed_execute_ThrowsInvalidInputFileException()
    {
        $this->expectException(InvalidInputFileException::class);

        $this->fileSystemHelper->method('getFileContent')->willReturn(null);

        $this->givenRemainingFileSystemChecksSucceed();

        $this->classesGenerator->execute();
    }

    public function test_ProvidedConfig_execute_IsFileReadableCalledOnGivenInputFile()
    {
        $this->fileSystemHelper->expects($this->once())->method('isFileReadable')->with(self::PROVIDED_INPUT_FILE)->willReturn(true);

        $this->givenRemainingFileSystemChecksSucceed();

        $this->classesGenerator->execute();
    }

    public function test_ProvidedConfig_execute_IsDirectoryWritableCalledOnGivenOutputDir()
    {
        $this->fileSystemHelper->expects($this->once())->method('isDirectoryWritable')->with(self::PROVIDED_OUTPUT_DIR)->willReturn(true);

        $this->givenRemainingFileSystemChecksSucceed();

        $this->classesGenerator->execute();
    }

    public function test_ProvidedConfig_execute_GetFileContentCalledOnGivenInputFile()
    {
        $this->fileSystemHelper->expects($this->once())->method('getFileContent')->with(self::PROVIDED_INPUT_FILE)->willReturn(self::PROVIDED_INPUT_FILE_CONTENT);

        $this->givenRemainingFileSystemChecksSucceed();

        $this->classesGenerator->execute();
    }

}