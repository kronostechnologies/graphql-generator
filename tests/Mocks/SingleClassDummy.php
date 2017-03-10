<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;

class SingleClassDummy extends SingleClass {

	/**
	 * @return BaseContentCreator
	 */
	public function getContentCreator() {
		// TODO: Implement getContentCreator() method.
	}

	/**
	 * @return string
	 */
	public function getStubFileName() {
		// TODO: Implement getStubFileName() method.
	}
}