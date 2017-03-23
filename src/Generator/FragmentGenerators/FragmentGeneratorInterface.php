<?php


namespace GraphQLGen\Generator\FragmentGenerators;


interface FragmentGeneratorInterface {
	/**
	 * @return string
	 */
	public function generateTypeDefinition();

	/**
	 * @return string
	 */
	public function getName();
}