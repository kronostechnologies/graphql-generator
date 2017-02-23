<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;

interface GeneratorWriterInterface {
	public function initialize();

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function generateFileForTypeGenerator($type);
}