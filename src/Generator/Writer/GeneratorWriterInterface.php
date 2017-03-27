<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;

interface GeneratorWriterInterface {
	public function initialize();

	public function finalize();

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 */
	public function generateFileForTypeGenerator($type);
}