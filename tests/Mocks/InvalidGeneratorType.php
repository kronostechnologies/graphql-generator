<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;

/**
 * Used for testing thrown exceptions by various type testing methods.
 * @package GraphQLGen\Tests\Mocks
 */
class InvalidGeneratorType implements FragmentGeneratorInterface {
	/**
	 * @return string
	 */
	public function getName() {
		// TODO: Implement getName() method.
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		// TODO: Implement generateTypeDefinition() method.
	}
}