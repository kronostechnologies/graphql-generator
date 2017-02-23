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
	 * @param Command $cmd
	 */
	public function configureCLI($cmd) {
		$cmd
			->addArgument('targetdir', InputArgument::REQUIRED, "Target output directory");
	}

	public function executeCLI(InputInterface $input, OutputInterface $output) {
		$this->targetDir = $input->getArgument('targetdir');
	}
}