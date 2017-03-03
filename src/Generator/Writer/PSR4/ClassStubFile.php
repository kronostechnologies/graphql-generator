<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\Resolver;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;
use GraphQLGen\Generator\Writer\StubFile;

/**
 * StubFile with special PSR-4 annotations.
 *
 * Class ClassStubFile
 * @package GraphQLGen\Generator\Writer\PSR4
 */
class ClassStubFile extends StubFile {
	const DUMMY_CLASSNAME = "ClassName";
	const DUMMY_NAMESPACE = "LocalNamespace";
	const VARIABLES_DECLARATION = "// @generate:Variables";

	/**
	 * @return null|string
	 */
	public function getNamespaceDeclarationLine() {
		return $this->getLineWithText(self::DUMMY_NAMESPACE);
	}

	/**
	 * @return null|string
	 */
	public function getDummyClassNameLine() {
		return $this->getLineWithText(self::DUMMY_CLASSNAME);
	}

	/**
	 * @return null|string
	 */
	public function getVariablesDeclarationLine() {
		return $this->getLineWithText(self::VARIABLES_DECLARATION);
	}

	public function writeNamespace($namespaceValue) {
		$this->replaceTextInStub(ClassStubFile::DUMMY_NAMESPACE, $namespaceValue);
	}

	public function removeNamespace() {
		$this->replaceTextInStub($this->getNamespaceDeclarationLine(), "");
	}

	/**
	 * @param string $className
	 */
	public function writeClassName($className) {
		$this->replaceTextInStub(ClassStubFile::DUMMY_CLASSNAME, $className);
	}

	/**
	 * @param string $formattedContent
	 */
	public function writeVariablesDeclarations($formattedContent) {
		$this->replaceTextInStub(ClassStubFile::VARIABLES_DECLARATION, $formattedContent);
	}

	/**
	 * @param SingleClass $class
	 * @return string
	 * @throws Exception
	 */
	public static function getStubFilenameForClass(SingleClass $class) {
		switch(get_class($class)) {
			case TypeStore::class:
				return 'typestore.stub';
			case Resolver::class:
				return 'resolver.stub';
			case ObjectType::class:
				/** @var ObjectType $class */
				return self::getStubFilenameForGeneratorType($class->getGeneratorType());
			default:
				throw new Exception("Stub not implemented for class generator type " . get_class($class));
		}
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public static function getStubFilenameForGeneratorType(BaseTypeGeneratorInterface $type) {
		switch(get_class($type)) {
			case Enum::class:
				return 'enum.stub';
			case Type::class:
				return 'object.stub';
			case Scalar::class:
				return 'scalar.stub';
			case InterfaceDeclaration::class:
				return 'interface.stub';
			default:
				throw new Exception("Stub not implemented for generator type " . get_class($type));
		}
	}

}