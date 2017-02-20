<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\StubFormatter;
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

	protected $_formatter;

	/**
	 * PSR4Writer constructor.
	 * @param string $targetDir
	 * @param string $baseNamespace
	 * @param bool $allowOverride
	 * @param StubFormatter $formatter
	 */
	public function __construct($targetDir, $baseNamespace, $allowOverride, $formatter) {
		$this->_targetDir = $targetDir;
		$this->_allowOverride = $allowOverride;
		$this->_baseNamespace = $baseNamespace;
		$this->_formatter = $formatter;
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
	 */
	public function generateFileForTypeGenerator($typeGenerator) {
		$dependencyPath = $typeGenerator->getDependencyPath();
		$usesDependencies = $typeGenerator->getDependencies();
		$classFQN = $this->generateNamespace($dependencyPath) . "\\" . $typeGenerator->getName();

		$stub = file_get_contents(__DIR__ . $typeGenerator->getStubFileName());
		$stubFile = new PSR4StubFile($stub);

		$typeDefinitionLine = $stubFile->getTypeDefinitionDeclarationLine();
		$typeDefinitionIndent = $this->_formatter->guessIndentsCount($typeDefinitionLine);
		$typeDefinitionUnformatted = $typeGenerator->generateTypeDefinition();
		$typeDefinitionFormatted = $this->_formatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndent);
		$stubFile->replaceTextInStub(PSR4StubFile::TYPE_DEFINITION_DECLARATION, $typeDefinitionFormatted);

		$className = $typeGenerator->getName();
		$stubFile->replaceTextInStub(PSR4StubFile::DUMMY_CLASSNAME, $className);

		$namespace = $this->generateNamespace($dependencyPath);
		$stubFile->replaceTextInStub(PSR4StubFile::DUMMY_NAMESPACE, $namespace);

		$variablesDeclarationLine = $stubFile->getVariablesDeclarationLine();
		$variablesDeclarationIndent = $this->_formatter->guessIndentsCount($variablesDeclarationLine);
		$variablesDeclarationNoIndent = $typeGenerator->getConstantsDeclaration();
		$variablesDeclarationIndented = $this->_formatter->indent($variablesDeclarationNoIndent, $variablesDeclarationIndent);
		$stubFile->replaceTextInstub(PSR4StubFile::VARIABLES_DECLARATION, $variablesDeclarationIndented);

		$usesDeclarations = $this->getDependenciesUses($usesDependencies);
		$stubFile->replaceTextInStub(PSR4StubFile::USES_DECLARATION, $usesDeclarations);

		// Finds type destination from FQN
		$relevantFQN = str_replace($this->_baseNamespace, "", $classFQN);
		$relevantFQN = str_replace("\\", "/", $relevantFQN);

		// Simply create the namespace + .php extension file
		file_put_contents($this->_targetDir . $relevantFQN . ".php", $stubFile->getContent());
	}
}