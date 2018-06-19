<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


interface DependentFragmentGeneratorInterface {
	/**
	 * @return string[]
	 */
	public function getDependencies();
}