<?php


namespace GraphQLGen\Generator\Writer;


use GraphQLGen\Generator\Types\BaseTypeGenerator;

interface GeneratorWriterInterface {
	public function initialize();

	public function finalize();

	/**
	 * @param BaseTypeGenerator $type
	 * @return string
	 */
	public function generateFileForTypeGenerator($type);
}