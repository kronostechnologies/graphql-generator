<?php


namespace GraphQLGen\Generator\FragmentGenerators;


interface DependentFragmentGeneratorInterface {
	/**
	 * @return string[]
	 */
	public function getDependencies();
}