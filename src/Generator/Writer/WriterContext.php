<?php


namespace GraphQLGen\Generator\Writer;


use Exception;
use GraphQLGen\Generator\Formatters\StubFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WriterContext {
	/**
	 * @var StubFormatter
	 */
	public $formatter;

	/**
	 * @var string
	 */
	public $targetDir;

	/**
	 * @var bool
	 */
	public $overwriteOldFiles = false;

	/**
	 * @var string
	 */
	public $stubsDir;

	/**
	 * @param Command $cmd
	 */
	public function configureCLI($cmd) {
		$cmd
			->addArgument('targetdir', InputArgument::REQUIRED, "Target output directory")
			->addOption('overwrite', 'o', null, "Optional. Activate if you want the files to be overwritten.")
			->addOption('stubs-dir', "s", InputArgument::OPTIONAL, "Optional. Directory of customized stubs to use.");
	}

	public function executeCLI(InputInterface $input, OutputInterface $output) {
		$this->targetDir = $input->getArgument('targetdir');
		$this->overwriteOldFiles = $input->getOption('overwrite');
		$this->stubsDir = $input->getOption('stubs-dir');

		$this->targetDir = $this->appendDirectorySlash($this->targetDir);
		$this->stubsDir = $this->stubsDir !== null ? $this->appendDirectorySlash($this->stubsDir) : null;
	}

	/**
	 * @param string $stubFileName
	 * @return string
	 */
	public function getStubFilePath($stubFileName) {
		if ($this->stubsDir === null) {
			return __DIR__ . "/" . $stubFileName;
		}

		return $this->stubsDir . $stubFileName;
	}

	/**
	 * @param string $directory
	 * @return string
	 */
	protected function appendDirectorySlash($directory) {
		if (strrpos($directory, "/") !== strlen($directory) - 1 &&
			strrpos($directory, "\\") !== strlen($directory) - 1) {
			return $directory . "/";
		}
	}

	/**
	 * @param string $suffixFilePath
	 * @return string
	 */
	public function getFilePath($suffixFilePath) {
		$targetDirWithoutEndTrailingSlash = rtrim($this->targetDir, "\\/");
		$suffixFilePathWithoutInitialTrailingSlash = ltrim($suffixFilePath, "\\/");

		$nonStandardizedPath = $targetDirWithoutEndTrailingSlash . DIRECTORY_SEPARATOR . $suffixFilePathWithoutInitialTrailingSlash;
		$standardizedPath = str_replace("\\", "/", $nonStandardizedPath);

		return $standardizedPath;
	}

	/**
	 * @param string $fileName
	 * @return string
	 */
	public function readFileContentToString($fileName) {
		return file_get_contents($fileName);
	}

	/**
	 * @param string $fileName
	 * @param string $content
	 * @param bool $forceOverride
	 * @throws Exception
	 */
	public function writeFile($fileName, $content, $forceOverride = false) {
		if(is_dir($fileName)) {
			throw new Exception("File {$fileName} is a directory, and cannot be written.");
		}

		if($this->fileExists($fileName)) {
			if ($forceOverride || $this->overwriteOldFiles) {
				unlink($fileName);
			} else {
				throw new Exception("File {$fileName} already exists.");
			}
		}

		file_put_contents($fileName, $content);
	}

	public function createDirectoryIfNonExistant($directory) {
		if (is_dir($directory)) {
			return;
		}

		mkdir($directory, 0777, true);
	}

	/**
	 * @param string $fileName
	 * @return bool
	 */
	public function fileExists($fileName) {
		return file_exists($fileName);
	}
}