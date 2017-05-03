<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use Exception;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\EnumFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\InterpretedTypes\Main\InputInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\InterfaceDeclarationInterpretedType;
use GraphQLGen\Generator\InterpretedTypes\Main\TypeDeclarationInterpretedType;
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
		$this->registerDependency("Type", 'GraphQL\Type\Definition\Type');
		$this->registerDependency("EnumType", 'GraphQL\Type\Definition\EnumType');
		$this->registerDependency("ScalarType", 'GraphQL\Type\Definition\ScalarType');
		$this->registerDependency("ObjectType", 'GraphQL\Type\Definition\ObjectType');
		$this->registerDependency("InterfaceType", 'GraphQL\Type\Definition\InterfaceType');
		$this->registerDependency("InputObjectType", 'GraphQL\Type\Definition\InputObjectType');
		$this->registerDependency("UnionObjectType", 'GraphQL\Type\Definition\UnionObjectType');
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
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public function getNamespaceForFragmentGenerator(FragmentGeneratorInterface $type) {
		switch(get_class($type)) {
			case TypeDeclarationFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types");
			case ScalarFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Scalars");
			case EnumFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Enums");
			case InterfaceFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Interfaces");
			case InputFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Input");
			case UnionFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Unions");
			default:
				throw new Exception("getNamespaceForFragmentGenerator not supported for type " . get_class($type));
		}
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 */
	public function getResolverNamespaceFromGenerator(FragmentGeneratorInterface $type) {
		switch(get_class($type)) {
			case TypeDeclarationFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types");
			case InterfaceFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Interfaces");
			case InputFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Inputs");
			case UnionFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "Resolvers", "Types", "Unions");
			default:
				throw new Exception("getResolverNamespaceFromGenerator not supported for type " . get_class($type));
		}
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public function getParentDependencyForFragmentGenerator(FragmentGeneratorInterface $type) {
		switch(get_class($type)) {
			case TypeDeclarationFragmentGenerator::class:
				return "ObjectType";
			case ScalarFragmentGenerator::class:
				return "ScalarType";
			case EnumFragmentGenerator::class:
				return "EnumType";
			case InterfaceFragmentGenerator::class:
				return "InterfaceType";
			case InputFragmentGenerator::class:
				return "InputObjectType";
			case UnionFragmentGenerator::class:
				return "UnionObjectType";
			default:
				throw new Exception("getParentDependencyForFragmentGenerator not supported for type " . get_class($type));
		}
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public function getClassQualifierForFragmentGenerator(FragmentGeneratorInterface $type) {
		switch(get_class($type)) {
			case TypeDeclarationFragmentGenerator::class:
			case ScalarFragmentGenerator::class:
			case EnumFragmentGenerator::class:
			case InputFragmentGenerator::class:
			case UnionFragmentGenerator::class:
				return 'class';
			case InterfaceFragmentGenerator::class:
				return 'trait';
			default:
				throw new Exception("getClassQualifierForFragmentGenerator not supported for type " . get_class($type));
		}
	}

	/**
	 * @param SingleClass $class
	 * @throws Exception
	 */
	public function registerClass(SingleClass $class) {
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
	public function registerDependency($dependencyName, $dependencyNamespace) {
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
	 */
	public function mapDependencyNameToClass($dependencyName, $class) {
		$this->registerDependency($dependencyName, $class->getFullQualifiedName());
		$this->registerClass($class);
	}

	/**
	 * @param $dependencyName
	 * @param ObjectType $class
	 */
	public function registerTypeStoreEntry($dependencyName, $class) {
		$this->getTypeStore()->addTypeImplementation($class);
		$this->getTypeStore()->addDependency($dependencyName);
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 *
	 */
	public function getDTONamespaceFromGenerator($type) {
		switch(get_class($type)) {
			case TypeDeclarationFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO");
			case InterfaceFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO", "Interfaces");
			case InputFragmentGenerator::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO", "Inputs");
			default:
				throw new Exception("getDTONamespaceFromGenerator not supported for type " . get_class($type));
		}
	}

	public function addTypestoreDependency($dependencyName) {

	}
}