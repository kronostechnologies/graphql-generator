<?php


namespace GraphQLGen\Generator\FragmentGenerators;


interface InterfacesDependableInterface {
	/**
	 * @return string[]
	 */
	public function getInterfaces();
}