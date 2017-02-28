<?php


namespace GraphQLGen\Generator\Formatters;


class AnnotationFormatter {
	/**
	 * @param string $typeName
	 * @param bool $isNullable
	 * @return string
	 */
	public function getAnnotationType($typeName, $isNullable) {
		if ($isNullable) {
			return $typeName . '|null';
		} else {
			return $typeName;
		}
	}

	/**
	 * @param string $typeName
	 * @param bool $isNullable
	 * @return string
	 */
	public function getAnnotationString($typeName, $isNullable) {
		return "/*** @type {$this->getAnnotationType($typeName, $isNullable)} **/";
	}
}