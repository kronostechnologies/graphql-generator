<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Writer\WriterContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PSR4WriterContext extends WriterContext {
	/**
	 * @var string
	 */
	public $namespace;

	/**
	 * @var string
	 */
	public $stubsPath;

	/**
	 * @var PSR4Resolver
	 */
	public $resolver;

	/**
	 * @param Command $cmd
	 */
	public function configureCLI($cmd) {
		parent::configureCLI($cmd);

		$cmd
			->addOption('psr4-namespace', "psr4-ns", InputArgument::OPTIONAL, "Optional. PSR4 base namespace.", "\\")
			->addOption('psr4-stubs-path', "psr4-stubs", InputArgument::OPTIONAL, "Optional. Directory of customized PSR4 stubs to use.");
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function executeCLI(InputInterface $input, OutputInterface $output) {
		parent::executeCLI($input, $output);

		$this->namespace = $input->getOption('psr4-namespace');
		$this->stubsPath = $input->getOption('psr4-stubs-path');

		$this->resolver = new PSR4Resolver($this->namespace);
	}
}