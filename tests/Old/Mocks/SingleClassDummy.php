<?php


namespace GraphQLGen\Tests\Old\Mocks;


use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\ContentCreator\BaseContentCreator;
use GraphQLGen\Old\Generator\Writer\Namespaced\Classes\SingleClass;

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