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
		$this->resolveDependency("Type", 'GraphQL\Type\Definition\Type');
		$this->resolveDependency("EnumType", 'GraphQL\Type\Definition\EnumType');
		$this->resolveDependency("ScalarType", 'GraphQL\Type\Definition\ScalarType');
		$this->resolveDependency("ObjectType", 'GraphQL\Type\Definition\ObjectType');
		$this->resolveDependency("InterfaceType", 'GraphQL\Type\Definition\InterfaceType');
		$this->resolveDependency("InputObjectType", 'GraphQL\Type\Definition\InterfaceType');
		$this->resolveDependency("UnionObjectType", 'GraphQL\Type\Definition\UnionObjectType');
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
	public function getNamespaceForGenerator(FragmentGeneratorInterface $type) {
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
				throw new Exception("getNamespaceForGenerator not supported for type " . get_class($type));
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
			default:
				throw new Exception("getResolverNamespaceFromGenerator not supported for type " . get_class($type));
		}
	}

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 */
	public function getParentDependencyForGenerator(FragmentGeneratorInterface $type) {
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
				throw new Exception("getParentDependencyForGenerator not supported for type " . get_class($type));
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

	/**
	 * @param FragmentGeneratorInterface $type
	 * @return string
	 * @throws Exception
	 *
	 */
	public function getDTONamespaceFromGenerator($type) {
		switch(get_class($type)) {
			case TypeDeclarationInterpretedType::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO");
			case InterfaceDeclarationInterpretedType::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO", "Interfaces");
			case InputInterpretedType::class:
				return PSR4Utils::joinAndStandardizeNamespaces($this->_baseNamespace, "DTO", "Inputs");
			default:
				throw new Exception("getDTONamespaceFromGenerator not supported for type " . get_class($type));
		}
	}
}