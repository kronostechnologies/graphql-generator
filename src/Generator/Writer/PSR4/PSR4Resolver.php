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
	protected $_baseNamespace;

	/**
	 * @param string $baseNamespace
	 */
	public function __construct($baseNamespace) {
		$this->_baseNamespace = $baseNamespace;

		$this->setStaticDependencies();
	}

	/**
	 * @return string[]
	 */
	public static function getBasicPSR4Structure() {
		return [
			'Types',
			'Types/Enums',
			'Types/Interfaces',
			'Types/Scalars',
		];
	}

	protected function setStaticDependencies() {
		$this->_resolvedTokens[$this->getDependencyNamespaceToken("Type")] = "GraphQL\\Type\\Definition\\Type";
		$this->_resolvedTokens[$this->getDependencyNamespaceToken("TypeStore")] = $this->joinAndStandardizeNamespaces($this->_baseNamespace, "TypeStore");
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getFQNForType(BaseTypeGeneratorInterface $type) {
		return $this->joinAndStandardizeNamespaces(
			$this->getNamespaceForType($type),
			$type->getName()
		);
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function storeFQNTokenForType(BaseTypeGeneratorInterface $type) {
		$fqn = $this->getFQNForType($type);
		$token = $this->getDependencyNamespaceToken($type->getName());
		$this->_resolvedTokens[$token] = $fqn;
	}

	/**
	 * @param string $fqn
	 * @return string
	 */
	public function getFilePathSuffixForFQN($fqn) {
		// Strips base namespace from FQN
		$standardizedBaseNS = $this->joinAndStandardizeNamespaces($this->_baseNamespace);
		$fqnWithoutBaseNS = substr($fqn, strlen($standardizedBaseNS));

		return str_replace("\\", "/", $fqnWithoutBaseNS) . ".php";
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getNamespaceForType(BaseTypeGeneratorInterface $type) {
		switch(get_class($type)) {
			case Type::class:
				return $this->joinAndStandardizeNamespaces($this->_baseNamespace, "Types");
			case Scalar::class:
				return $this->joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Scalars");
			case Enum::class:
				return $this->joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Enums");
			case InterfaceDeclaration::class:
				return $this->joinAndStandardizeNamespaces($this->_baseNamespace, "Types", "Interfaces");
		}
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
	public function joinAndStandardizeNamespaces(...$namespaceParts) {
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
		if($this->isNonPrimaryTypeDependencyPresent($dependencies)) {
			$dependencies[] = 'TypeStore';
		}

		return array_unique(
			array_map(function ($dependency) {
				return $this->getDependencyNamespaceToken($dependency);
			}, $dependencies)
		);
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
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
		}
	}

	/**
	 * @return \string[]
	 */
	public function getAllResolvedTokens() {
		return $this->_resolvedTokens;
	}

	/**
	 * @param string[] $dependencies
	 * @return bool
	 */
	protected function isNonPrimaryTypeDependencyPresent($dependencies) {
		return count(
				array_filter($dependencies, function ($dependency) {
					return !isset(TypeUsage::$PRIMARY_TYPES_MAPPINGS[$dependency]);
				})
			) > 0;
	}
}