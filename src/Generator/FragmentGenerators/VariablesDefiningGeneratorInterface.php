<?php


namespace GraphQLGen\Generator\FragmentGenerators;


interface VariablesDefiningGeneratorInterface {
	/**
	 * @return string
	 */
	public function getVariablesDeclarations();
}