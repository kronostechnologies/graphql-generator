<?php


namespace GraphQLGen\Generator\Types;


interface BaseTypeGeneratorInterface {
	/**
	 * @return string
	 */
	public function generateTypeDefinition();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations();

	/**
	 * @return string[]
	 */
	public function getDependencies();
}