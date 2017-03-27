<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;

interface GeneratorWriterInterface {
	/**
	 * @param FragmentGeneratorInterface $fragmentGenerator
	 * @return string
	 */
	public function generateFileForTypeGenerator($fragmentGenerator);

	public function initialize();

	public function finalize();
}