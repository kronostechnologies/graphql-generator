<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

class ClassMapper {
	/**
	 * @var SingleClass[]
	 */
	protected $_classes;

	/**
	 * @var TypeStore
	 */
	protected $_typeStore;

	/**
	 * @var string
	 */
	protected $_baseNamespace;

	/**
	 * @var string[]
	 */
	protected $_resolvedDependencies;

	public function __construct() {
		$this->_classes = [];
		$this->_resolvedDependencies = [];
	}

	/**
	 * @return mixed
	 */
	public function getTypeStore() {
		return $this->_typeStore;
	}

	/**
	 * @param mixed $typeStore
	 */
	public function setTypeStore($typeStore) {
		$this->_typeStore = $typeStore;
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getNamespaceForGenerator(BaseTypeGeneratorInterface $type) {
		switch(get_class($type)) {
			case Type::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types");
			case Scalar::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Scalars");
			case Enum::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Enums");
			case InterfaceDeclaration::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Interfaces");
			default:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace);
		}
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public function getStubFilenameForType(BaseTypeGeneratorInterface $type) {
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
				throw new Exception("Stub not implemented for type " . get_class($type));
		}
	}

	/**
	 * @param SingleClass $class
	 */
	public function addClass(SingleClass $class) {
		// ToDo: Multiple definitions check
		$this->_classes[] = $class;
	}

	/**
	 * @return SingleClass[]
	 */
	public function getClasses() {
		return $this->_classes;
	}

	/**
	 * @param string $dependencyName
	 * @param string $dependencyNamespace
	 */
	public function resolveDependency($dependencyName, $dependencyNamespace) {
		$this->_resolvedDependencies[$dependencyName] = $dependencyNamespace;
		// ToDo: Loop through classes and change unresolved dependencies token to dependency namespace
	}

	/**
	 * @return string
	 */
	public function getBaseNamespace() {
		return $this->_baseNamespace;
	}

	/**
	 * @param string $baseNamespace
	 */
	public function setBaseNamespace($baseNamespace) {
		$this->_baseNamespace = $baseNamespace;
	}
}