<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


interface VariablesDefiningGeneratorInterface {
	/**
	 * @return string
	 */
	public function getVariablesDeclarations();
}