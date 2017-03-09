<?php


namespace GraphQLGen\Generator\Writer\PSR4\Classes;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ContentCreator\ObjectTypeContent;

class ObjectType extends SingleClass {
	/**
	 * @var BaseTypeGeneratorInterface
	 */
	protected $_generatorType;

	const ENUM_STUB = 'enum.stub';
	const OBJECT_STUB = 'object.stub';
	const SCALAR_STUB = 'scalar.stub';
	const INTERFACE_STUB = 'interface.stub';

	/**
	 * @return BaseTypeGeneratorInterface
	 */
	public function getGeneratorType() {
		return $this->_generatorType;
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
	 */
	public function setGeneratorType(BaseTypeGeneratorInterface $generatorType) {
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
			case Enum::class:
				return self::ENUM_STUB;
			case Type::class:
				return self::OBJECT_STUB;
			case Scalar::class:
				return self::SCALAR_STUB;
			case InterfaceDeclaration::class:
				return self::INTERFACE_STUB;
			default:
				throw new Exception("Stub not implemented for generator type " . get_class($this->getGeneratorType()));
		}
	}
}