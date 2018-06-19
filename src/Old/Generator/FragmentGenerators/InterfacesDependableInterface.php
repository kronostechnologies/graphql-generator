<?php


namespace GraphQLGen\Old\Generator\FragmentGenerators;


interface InterfacesDependableInterface {
	/**
	 * @return string[]
	 */
	public function getInterfaces();
}