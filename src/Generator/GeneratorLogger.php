<?php


namespace GraphQLGen\Generator;


use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorLogger implements LoggerInterface {
	/**
	 * @var OutputInterface
	 */
	protected $_consoleOutput;

	/**
	 * GeneratorLogger constructor.
	 * @param OutputInterface $consoleOutput
	 */
	public function __construct($consoleOutput) {
		$this->_consoleOutput = $consoleOutput;
	}

	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function emergency($message, array $context = array()) {
		$this->_consoleOutput->writeln("<emergency>{$message}</emergency>");
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function alert($message, array $context = array()) {
		$this->_consoleOutput->writeln("<alert>{$message}</alert>");
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function critical($message, array $context = array()) {
		$this->_consoleOutput->writeln("<critical>{$message}</critical>");
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function error($message, array $context = array()) {
		$this->_consoleOutput->writeln("<error>{$message}</error>");
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function warning($message, array $context = array()) {
		$this->_consoleOutput->writeln("<warning>{$message}</warning>");
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function notice($message, array $context = array()) {
		$this->_consoleOutput->writeln("<notice>{$message}</notice>");
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function info($message, array $context = array()) {
		$this->_consoleOutput->writeln("<info>{$message}</info>");
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function debug($message, array $context = array()) {
		$this->_consoleOutput->writeln("<debug>{$message}</debug>");
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 *
	 * @return void
	 */
	public function log($level, $message, array $context = array()) {
		$this->_consoleOutput->writeln("{$message}");
	}
}