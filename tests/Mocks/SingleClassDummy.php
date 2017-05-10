<?php


namespace GraphQLGen\Tests\Mocks;


use GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Generator\Writer\Namespaced\Classes\SingleClass;

class SingleClassDummy extends SingleClass {

	/**
	 * @return BaseContentCreator
	 */
	public function getContentCreator() {

	}

	/**
	 * @return string
	 */
	public function getStubFileName() {

	}
}