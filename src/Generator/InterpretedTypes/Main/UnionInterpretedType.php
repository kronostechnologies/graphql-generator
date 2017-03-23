<?php


namespace GraphQLGen\Generator\InterpretedTypes\Main;


use GraphQLGen\Generator\InterpretedTypes\DescribableTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\NamedTypeTrait;
use GraphQLGen\Generator\InterpretedTypes\Nested\TypeUsageInterpretedType;

class UnionInterpretedType {
	use NamedTypeTrait, DescribableTypeTrait;

	/**
	 * @var TypeUsageInterpretedType[]
	 */
	protected $_types;

	/**
	 * @return TypeUsageInterpretedType[]
	 */
	public function getTypes() {
		return $this->_types;
	}

	/**
	 * @param TypeUsageInterpretedType[] $types
	 */
	public function setTypes($types) {
		$this->_types = $types;
	}
}