<?php


namespace GraphQLGen\Generator\Types;


interface GeneratorTypeInterface {
	/**
	 * @return string
	 */
	public function GenerateTypeDefinition();

	/**
	 * @return string
	 */
	public function GetStubFile();

	/**
	 * @return string
	 */
	public function GetNamespacePart();

	/**
	 * @return string
	 */
	public function GetClassName();

	/**
	 * @return string|null
	 */
	public function GetConstantsDeclaration();

	/**
	 * @return string|null
	 */
	public function GetUsesDeclaration();
}