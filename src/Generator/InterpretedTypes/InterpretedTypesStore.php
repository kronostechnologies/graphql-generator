<?php


namespace GraphQLGen\Generator\InterpretedTypes;


use Exception;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;

class InterpretedTypesStore {
	/**
	 * @var NamedTypeTrait[]
	 */
	protected $_interpretedTypes = [];

	/**
	 * @param NamedTypeTrait $interpretedType
	 * @throws Exception
	 */
	public function registerInterpretedType($interpretedType) {
		if (!$this->isMainInterpretedType($interpretedType)) {
			throw new Exception("Expected fragment generator to be of main type. Given type: " . get_class($interpretedType));
		}

		if ($this->containsInterpretedType($interpretedType)) {
			throw new Exception("Fragment generator of type " . $interpretedType->getName() . " is already contained in the store. This possibly means the named type is duplicated in your schema.");
		}

		$this->_interpretedTypes[] = $interpretedType;
	}

	/**
	 * @param string $typeName
	 * @return NamedTypeTrait|null
	 */
	public function getInterpretedTypeByName($typeName) {
		$results = array_filter($this->_interpretedTypes, function ($interpretedType) use ($typeName) {
			/** @var NamedTypeTrait $interpretedType */
			return $interpretedType->getName() === $typeName;
		});

		return array_shift($results);
	}

	/**
	 * @param NamedTypeTrait $interpretedType
	 * @return bool
	 */
	protected function containsInterpretedType($interpretedType) {
		return $this->getInterpretedTypeByName($interpretedType->getName()) !== null;
	}

	/**
	 * @param NamedTypeTrait $interpretedType
	 * @return bool
	 */
	public function isMainInterpretedType($interpretedType) {
		return
			($interpretedType instanceof EnumInterpretedType) ||
			($interpretedType instanceof InputInterpretedType) ||
			($interpretedType instanceof InterfaceDeclarationInterpretedType) ||
			($interpretedType instanceof ScalarInterpretedType) ||
			($interpretedType instanceof TypeDeclarationInterpretedType) ||
			($interpretedType instanceof UnionInterpretedType);
	}

	/**
	 * @return NamedTypeTrait[]
	 */
	public function getInterpretedTypes() {
		return $this->_interpretedTypes;
	}
}