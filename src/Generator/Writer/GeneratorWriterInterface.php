<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

interface GeneratorWriterInterface {
	public function initialize();

	/**
	 * @param BaseTypeGeneratorInterface $typeGenerator
	 * @return string
	 */
	public function generateFileForTypeGenerator($typeGenerator);
}