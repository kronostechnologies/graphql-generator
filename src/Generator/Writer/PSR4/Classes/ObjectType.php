<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;

class ObjectType extends SingleClass {
	/**
	 * @var FragmentGeneratorInterface
	 */
	protected $_generatorType;

	const ENUM_STUB = 'enum.stub';
	const OBJECT_STUB = 'object.stub';
	const SCALAR_STUB = 'scalar.stub';
	const INTERFACE_STUB = 'interface.stub';
	const INPUT_STUB = 'input.stub';
	const UNION_STUB = 'union.stub';

	/**
	 * @return FragmentGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param FragmentGeneratorInterface $generatorType
	 */
	public function setGeneratorType($generatorType) {
		$this->_generatorType = $generatorType;
	}

	/**
	 * @return ObjectTypeContent
	 */
	public function getContentCreator() {
		$objectTypeContent = new ObjectTypeContent();
		$objectTypeContent->setObjectTypeClass($this);
		$objectTypeContent->setGeneratorType($this->getGeneratorType());

		return $objectTypeContent;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getStubFileName() {
		switch(get_class($this->getGeneratorType())) {
			case EnumFragmentGenerator::class:
				return self::ENUM_STUB;
			case TypeDeclarationFragmentGenerator::class:
				return self::OBJECT_STUB;
			case ScalarFragmentGenerator::class:
				return self::SCALAR_STUB;
			case InterfaceFragmentGenerator::class:
				return self::INTERFACE_STUB;
			case InputFragmentGenerator::class:
				return self::INPUT_STUB;
			case UnionFragmentGenerator::class:
				return self::UNION_STUB;
			default:
				throw new Exception("Stub not implemented for generator type " . get_class($this->getGeneratorType()));
		}
	}
}