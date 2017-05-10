<?php


namespace GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator;


abstract class BaseContentCreator {
	public abstract function getContent();

	public abstract function getVariables();

	public abstract function getNamespace();

	public abstract function getClassName();

	public abstract function getParentClassName();
}