<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\Formatters\StubFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class WriterContext {
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
	 * @param Command $cmd
	 */
	public function configureCLI($cmd) {
		$cmd
			->addArgument('targetdir', InputArgument::REQUIRED, "Target output directory")
			->addOption('overwrite', 'o', null, "Optional. Activate if you want the files to be overwritten.");
	}

	public function executeCLI(InputInterface $input, OutputInterface $output) {
		$this->targetDir = $input->getArgument('targetdir');
		$this->overwriteOldFiles = $input->getOption('overwrite');

		// Append slash to targetdir
		if (strrpos($this->targetDir, "/") !== strlen($this->targetDir) - 1 &&
			strrpos($this->targetDir, "\\") !== strlen($this->targetDir) - 1) {
			$this->targetDir .= '/';
		}
	}

	/**
	 * @param string $suffixFilePath
	 * @return string
	 */
	public function getFilePath($suffixFilePath) {
		$targetDirWithoutTrailingSlash = rtrim($this->targetDir, "\\");
		$targetDirWithoutTrailingSlash = rtrim($targetDirWithoutTrailingSlash, "/");

		$suffixFilePathWithoutTrailingSlash = ltrim($suffixFilePath, "\\");
		$suffixFilePathWithoutTrailingSlash = ltrim($suffixFilePathWithoutTrailingSlash, "/");

		$nonStandardizedPath = $targetDirWithoutTrailingSlash . DIRECTORY_SEPARATOR . $suffixFilePathWithoutTrailingSlash;
		$standardizedPath = str_replace("\\", "/", $nonStandardizedPath);

		return $standardizedPath;
	}
}