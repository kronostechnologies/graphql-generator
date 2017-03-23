<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\InterpretedTypes\Main\EnumInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\ScalarInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\UnionInterpretedType;
use GraphQLGen\Generator\Types\BaseTypeGenerator;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\Input;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Types\Union;
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
			case EnumInterpretedType::class:
				return self::ENUM_STUB;
			case TypeDeclarationInterpretedType::class:
				return self::OBJECT_STUB;
			case ScalarInterpretedType::class:
				return self::SCALAR_STUB;
			case InterfaceDeclarationInterpretedType::class:
				return self::INTERFACE_STUB;
			case InputInterpretedType::class:
				return self::INPUT_STUB;
			case UnionInterpretedType::class:
				return self::UNION_STUB;
			default:
				throw new Exception("Stub not implemented for generator type " . get_class($this->getGeneratorType()));
		}
	}
}