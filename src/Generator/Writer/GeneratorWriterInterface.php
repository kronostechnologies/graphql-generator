<?php


namespace GraphQLGen\Generator\Writer;


interface GeneratorWriterInterface {
	public function initialize();

	/**
	 * @param string $classFQN
	 * @param string $classContent
	 */
	public function writeClass($classFQN, $classContent);
}