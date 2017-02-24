<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\SubTypes\TypeUsage;
use GraphQLGen\Generator\Types\Type;

class PSR4Resolver {
	/**
	 * @var string[]
	 */
	protected $_resolvedTokens = [];

	/**
	 * @var string
	 */
	public $baseNamespace;

	/**
	 * @return string[]
	 */
	public static function getBasicPSR4Structure() {
		return [
			'', // ToDo: remove this
			'Types',
			'Types/Enums',
			'Types/Interfaces',
			'Types/Scalars',
		];
	}

	/**
	 * @param string $baseNamespace
	 */
	public function __construct($baseNamespace) {
		$this->baseNamespace = $baseNamespace;

		$this->setStaticDependencies($baseNamespace);
	}

	public function setStaticDependencies($baseNamespace) {
		$this->_resolvedTokens[$this->getDependencyNamespaceToken("Type")] = "GraphQL\\Type\\Definition\\Type";
		$this->_resolvedTokens[$this->getDependencyNamespaceToken("TypeStore")] = $this->joinNamespaces($baseNamespace, "TypeStore");
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getFQNForType($type) {
		return $this->joinNamespaces(
			$this->getNamespaceForType($type),
			$type->getName()
		);
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function storeFQNTokenForType($type) {
		$fqn = $this->getFQNForType($type);
		$token = $this->getDependencyNamespaceToken($type->getName());
		$this->_resolvedTokens[$token] = $fqn;
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getFilePathForType($type) {
		switch(get_class($type)) {
			case Type::class:
				return $this->joinNamespaces("Types");
			case Scalar::class:
				return $this->joinNamespaces("Types", "Scalars");
			case Enum::class:
				return $this->joinNamespaces("Types", "Enums");
			case InterfaceDeclaration::class:
				return $this->joinNamespaces("Types", "Interfaces");
		}
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getNamespaceForType($type) {
		return $this->joinNamespaces(
			$this->baseNamespace,
			$this->getFilePathForType($type)
		);
	}

	/**
	 * @param string $dependencyName
	 * @return string
	 */
	public function getDependencyNamespaceToken($dependencyName) {
		return 'NS/"' . $dependencyName . '";';
	}

	/**
	 * @param string[] ...$namespaceParts
	 * @return string
	 */
	public function joinNamespaces(...$namespaceParts) {
		$namespacePartsWithoutTrailingSlashes = array_map(function ($namespacePart) {
			$namespacePart = str_replace("/", "\\", $namespacePart);
			return trim($namespacePart, "\\");
		}, $namespaceParts);

		return trim(implode("\\", $namespacePartsWithoutTrailingSlashes), "\\");
	}

	/**
	 * @param string[] $dependencies
	 * @return string[]
	 */
	public function generateTokensFromDependencies($dependencies) {
		// Checks if a non-primary type is a dependency. If so, add TypeStore
		if ($this->isNonPrimaryTypeDependencyPresent($dependencies)) {
			$dependencies[] = 'TypeStore';
		}

		return array_unique(
			array_map(function ($dependency) {
				return $this->getDependencyNamespaceToken($dependency);
			}, $dependencies)
		);
	}

	/**
	 * @param string[] $dependencies
	 * @return bool
	 */
	protected function isNonPrimaryTypeDependencyPresent($dependencies) {
		return count(
			array_filter($dependencies,function ($dependency) {
				return !isset(TypeUsage::$PRIMARY_TYPES_MAPPINGS[$dependency]);
			})
		) > 0;
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getStubFilenameForType($type) {
		switch(get_class($type)) {
			case Enum::class:
				return '/stubs/enum.stub';
			case Type::class:
				return '/stubs/object.stub';
			case Scalar::class:
				return '/stubs/scalar.stub';
			case InterfaceDeclaration::class:
				return '/stubs/interface.stub';
		}
	}

	/**
	 * @return \string[]
	 */
	public function getAllResolvedTokens() {
		return $this->_resolvedTokens;
	}

	/**
	 * @param string $namespace
	 * @return string
	 */
	public function getNamespaceDirectory($namespace) {
		$baseNamespaceTrimmed = $this->joinNamespaces($this->baseNamespace);
		$namespaceTrimmed = $this->joinNamespaces($namespace);

		return trim(substr($namespaceTrimmed, strlen($baseNamespaceTrimmed)), "\\");
	}
}