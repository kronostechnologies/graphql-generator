<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQL\Language\AST\Type;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Writer\GeneratorWriterInterface;

class PSR4Writer implements GeneratorWriterInterface {
	/**
	 * @var string
	 */
	protected $_targetDir;

	/**
	 * @var bool
	 */
	protected $_allowOverride;

	/**
	 * @var string
	 */
	protected $_baseNamespace;

	public function __construct($targetDir, $baseNamespace, $allowOverride) {
		$this->_targetDir = $targetDir;
		$this->_allowOverride = $allowOverride;
		$this->_baseNamespace = $baseNamespace;
	}

	public function initialize() {
		foreach(self::getBasicPSR4Structure() as $structureFolder) {
			mkdir(getcwd() . '/' . $this->_targetDir . $structureFolder);
		}
	}

	/**
	 * @return string[]
	 */
	public static function getBasicPSR4Structure() {
		return [
			'Types',
			'Types/Enum',
			'Types/Interface',
			'Types/Scalar',
			'Types/Input',
		];
	}

	/**
	 * @param string[] $dependencyPath
	 * @return string
	 */
	protected function generateNamespace($dependencyPath) {
		return $this->_baseNamespace . "\\" . implode("\\", $dependencyPath);
	}

	/**
	 * @param string $dependency
	 * @return string
	 */
	protected function getDependencyUse($dependency) {
		switch ($dependency) {
			case 'Type':
				return "use GraphQL\\Type\\Definition\\Type;";
			case 'TypeStore':
				return "use " . $this->_baseNamespace . "\\TypeStore;";
		}
	}

	/**
	 * @param string[] $dependencies
	 * @return string
	 */
	protected function getDependenciesUses($dependencies) {
		$uses = [];

		foreach ($dependencies as $dependency) {
			$uses[] = $this->getDependencyUse($dependency);
		}

		$uniqueUses = array_unique($uses);

		return implode("\n", $uniqueUses);
	}

	/**
	 * @param BaseTypeGeneratorInterface $typeGenerator
	 * @return string
	 */
	public function generateFileForTypeGenerator($typeGenerator) {
		$dependencyPath = $typeGenerator->getDependencyPath();
		$usesDependencies = $typeGenerator->getDependencies();
		$classFQN = $this->generateNamespace($dependencyPath) . "\\" . $typeGenerator->getName();

		$stub = file_get_contents(__DIR__ . $typeGenerator->getStubFileName());
		$stub = str_replace('$TypeDefinitionDummy', $typeGenerator->generateTypeDefinition(), $stub);
		$stub = str_replace('DummyClass', $typeGenerator->getName(), $stub);
		$stub = str_replace('DummyNamespace', $this->generateNamespace($dependencyPath), $stub);
		$stub = str_replace('"ConstantsDeclaration";', $typeGenerator->getConstantsDeclaration(), $stub);
		$stub = str_replace('"UsesDeclaration";', $this->getDependenciesUses($usesDependencies), $stub);

		// Finds type destination from FQN
		$relevantFQN = str_replace($this->_baseNamespace, "", $classFQN);
		$relevantFQN = str_replace("\\", "/", $relevantFQN);

		// Simply create the namespace + .php extension file
		file_put_contents($this->_targetDir . $relevantFQN . ".php", $stub);
	}
}