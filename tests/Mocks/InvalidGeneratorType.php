<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;

/**
 * Used for testing thrown exceptions by various type testing methods.
 * @package GraphQLGen\Tests\Mocks
 */
class InvalidGeneratorType implements FragmentGeneratorInterface {
	/**
	 * @return string
	 */
	public function getName() {

	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {

	}
}