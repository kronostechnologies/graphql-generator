<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use Exception;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Writer\PSR4\Classes\ObjectType;
use GraphQLGen\Generator\Writer\PSR4\Classes\SingleClass;
use GraphQLGen\Generator\Writer\PSR4\Classes\TypeStore;

/**
 * Maps a dependency name to a full qualified name, and handles namespace generation for generator types.
 *
 * Class ClassMapper
 * @package GraphQLGen\Generator\Writer\PSR4
 */
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

	public function setInitialMappings() {
		$this->resolveDependency("Type", 'GraphQL\Type\Definition\Type');
	}

	/**
	 * @return TypeStore
	 */
	public function getTypeStore() {
		return $this->_typeStore;
	}

	/**
	 * @param TypeStore $typeStore
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
	 */
	public function getResolverNamespaceFromGenerator(BaseTypeGeneratorInterface $type) {
		switch(get_class($type)) {
			case Type::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types");
			case Scalar::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Scalars");
			case Enum::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Enums");
			case InterfaceDeclaration::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Interfaces");
			default:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace);
		}
	}

	/**
	 * @param SingleClass $class
	 * @throws Exception
	 */
	public function addClass(SingleClass $class) {
		$similarClasses = array_filter($this->_classes, function (SingleClass $comparedAgainstClass) use ($class) {
			return ($comparedAgainstClass->getClassName() === $class->getClassName());
		});

		if (!empty($similarClasses)) {
			throw new Exception("Two resolved classes have the same name ({$class->getClassName()}). This usually means you declared a type of the same name twice in your GraphQL Schema.");
		}

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
		$this->_baseNamespace = PSR4Utils::joinAndStandardizeNamespaces($baseNamespace);
	}

	/**
	 * @param string $dependencyName
	 * @return string
	 * @throws Exception
	 */
	public function getResolvedDependency($dependencyName) {
		if(!array_key_exists($dependencyName, $this->getResolvedDependencies())) {
			throw new Exception("Dependency {$dependencyName} not found. Is the type declared in your graphqls file?");
		}

		return $this->getResolvedDependencies()[$dependencyName];
	}

	/**
	 * @return string[]
	 */
	public function getResolvedDependencies() {
		return $this->_resolvedDependencies;
	}

	/**
	 * @param string $dependencyName
	 * @param SingleClass $class
	 * @param bool $asTypeImplementation
	 */
	public function mapClass($dependencyName, SingleClass $class, $asTypeImplementation) {
		$this->resolveDependency($dependencyName, $class->getFullQualifiedName());
		$this->addClass($class);

		if($asTypeImplementation) {
			/** @var ObjectType $class */
			$this->getTypeStore()->addTypeImplementation($class);
		}
	}
}