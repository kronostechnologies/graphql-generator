<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Scalar;
use GraphQLGen\Generator\Types\Type;

class PSR4Resolver {
	/**
	 * @var string
	 */
	public $baseNamespace;

	/**
	 * @return string[]
	 */
	public static function getBasicPSR4Structure() {
		return [
			'',
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
	}

	/**
	 * @param string $secondaryNamespace
	 * @return string
	 */
	public function getFullNamespace($secondaryNamespace) {
		return $this->baseNamespace . $secondaryNamespace;
	}

	/**
	 * @param string $namespace
	 * @return string
	 */
	public function removeNamespaceTrailingSlashes($namespace) {
		return trim($namespace, "\\");
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getFullNamespaceForType($type) {
		$typeNamespace = $this->getNamespaceForType($type);

		return $this->getFullNamespace($typeNamespace);
	}

	/**
	 * @param BaseTypeGeneratorInterface $type
	 * @return string
	 */
	public function getFQNForType($type) {
		$typeNamespace = $this->getNamespaceForType($type);

		return $this->getFullNamespace($typeNamespace) . $type->getName();
	}

	/**
	 * @param string $dependency
	 * @return string
	 */
	protected function getNamespaceFromDependency($dependency) {
		switch($dependency) {
			case 'Type':
				return "use GraphQL\\Type\\Definition\\Type;";
			case 'TypeStore':
				return "use " . $this->baseNamespace . "\\TypeStore;";
		}
	}

	/**
	 * @param string[] $dependencies
	 * @return string
	 */
	public function getAllNamespacesFromDependencies($dependencies) {
		$uses = [];

		foreach($dependencies as $dependency) {
			$uses[] = $this->getNamespaceFromDependency($dependency);
		}

		$uniqueUses = array_unique($uses);

		return implode("\n", $uniqueUses);
	}

	protected function getNamespaceForType($type) {
		switch(get_class($type)) {
			case Enum::class:
				return "Types\\Enums\\";
			case Type::class:
				return "Types\\";
			case Scalar::class:
				return "Types\\Scalars\\";
			case InterfaceDeclaration::class:
				return 'Types\\Interfaces\\';
		}
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
}