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
	 */
	public function getStubFileName() {
		switch(get_class($this->getGeneratorType())) {
			case Enum::class:
				return 'enum.stub';
			case Type::class:
				return 'object.stub';
			case Scalar::class:
				return 'scalar.stub';
			case InterfaceDeclaration::class:
				return 'interface.stub';
			default:
				throw new Exception("Stub not implemented for generator type " . get_class($this->getGeneratorType()));
		}
	}
}