<?php


namespace GraphQLGen\FileSystem;


class FileSystemHelper
{
    /**
     * @param string $fileName
     * @return string|null
     */
    public function getFileContent($fileName)
    {
        $content = file_get_contents($fileName);

        if ($content === false) {
            return false;
        }

        return $content;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function isFileReadable($fileName)
    {
        return is_file($fileName);
    }

    /**
     * @param string $directoryPath
     * @return bool
     */
    public function isDirectoryWritable($directoryPath)
    {
        return is_dir($directoryPath);
    }
}