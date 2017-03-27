<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;

interface GeneratorWriterInterface {
	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 */
	public function generateFileForTypeGenerator($type);

	public function initialize();

	public function finalize();
}