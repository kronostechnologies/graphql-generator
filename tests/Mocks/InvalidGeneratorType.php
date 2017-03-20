<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Generator\Types\BaseTypeGenerator;

/**
 * Used for testing thrown exceptions by various type testing methods.
 * @package GraphQLGen\Tests\Mocks
 */
class InvalidGeneratorType extends BaseTypeGenerator {

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		// TODO: Implement generateTypeDefinition() method.
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		// TODO: Implement getVariablesDeclarations() method.
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		// TODO: Implement getDependencies() method.
	}
}