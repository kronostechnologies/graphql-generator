<?php


namespace GraphQLGen\Old\Generator\Writer\Namespaced;


use GraphQLGen\Old\Generator\Formatters\ClassFormatter;
use GraphQLGen\Old\Generator\Writer\WriterContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Writer configuration.
 *
 * Class PSR4WriterContext
 * @package GraphQLGen\Generator\Writer\Namespaced
 */
class NamespacedWriterContext extends WriterContext {
	/**
	 * @var string
	 */
	public $namespace;

    /**
     * @var bool
     */
	public $skipResolver;

	/**
	 * @param Command $cmd
	 */
	public function configureCLI($cmd) {
		parent::configureCLI($cmd);

		$cmd
			->addOption('namespaced-target-namespace', null, InputArgument::OPTIONAL, "Optional. Namespaced base namespace.", "\\");
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function executeCLI(InputInterface $input, OutputInterface $output) {
		parent::executeCLI($input, $output);

		$this->namespace = $input->getOption('namespaced-target-namespace');
		$this->skipResolver = $input->getOption('mode') === 'types';
	}

	/**
	 * @param string $stubFileName
	 * @return string
	 */
	public function getStubFilePath($stubFileName) {
		if($this->stubsDir === null) {
			return __DIR__ . "/stubs/" . $stubFileName;
		}

		return $this->stubsDir . $stubFileName;
	}

	/**
	 * @return ClassFormatter
	 */
	public function getConfiguredClassFormatter() {
		$classFormatter = new ClassFormatter();
		$classFormatter->setUseSpaces($this->formatter->useSpaces);
		$classFormatter->setTabSize($this->formatter->tabSize);

		return $classFormatter;
	}
}