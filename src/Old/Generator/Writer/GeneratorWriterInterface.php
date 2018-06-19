<?php


namespace GraphQLGen\Old\Generator\Writer;


use GraphQLGen\Old\Generator\FragmentGenerators\FragmentGeneratorInterface;

interface GeneratorWriterInterface {
	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return string
	 */
	public function generateFileForTypeGenerator($fragmentGenerator);

	public function initialize();

	public function finalize();
}