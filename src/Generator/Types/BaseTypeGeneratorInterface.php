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
	public function getStubFileName();

	/**
	 * @return string[]
	 */
	public function getDependencyPath();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string|null
	 */
	public function getConstantsDeclaration();

	/**
	 * @return string[]
	 */
	public function getDependencies();
}