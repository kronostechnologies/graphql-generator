<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

class InvalidGeneratorType implements BaseTypeGeneratorInterface {

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		// TODO: Implement generateTypeDefinition() method.
	}

	/**
	 * @return string
	 */
	public function getName() {
		// TODO: Implement getName() method.
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