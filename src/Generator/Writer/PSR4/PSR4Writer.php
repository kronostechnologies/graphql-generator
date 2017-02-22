<?php


namespace GraphQLGen\Generator\Writer\PSR4;


use GraphQLGen\Generator\StubFormatter;
use GraphQLGen\Generator\Types\BaseTypeGeneratorInterface;
use GraphQLGen\Generator\Types\Enum;
use GraphQLGen\Generator\Types\InterfaceDeclaration;
use GraphQLGen\Generator\Types\Type;
use GraphQLGen\Generator\Types\Scalar;
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
	 * @param string $baseNamespace
	 * @return string
	 */
	protected function generateNamespace($baseNamespace) {
		return $this->_baseNamespace . "\\" . $baseNamespace;
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
		$baseNamespace = $this->getNamespaceSubpart($typeGenerator);
		$usesDependencies = $typeGenerator->getDependencies();
		$classFQN = $this->generateNamespace($baseNamespace) . "\\" . $typeGenerator->getName();

		$stub = file_get_contents(__DIR__ . $this->getStubFilename($typeGenerator));
		$stubFile = new PSR4StubFile($stub);

		$typeDefinitionLine = $stubFile->getTypeDefinitionDeclarationLine();
		$typeDefinitionIndent = $this->_formatter->guessIndentsCount($typeDefinitionLine);
		$typeDefinitionUnformatted = $typeGenerator->generateTypeDefinition();
		$typeDefinitionFormatted = ltrim($this->_formatter->formatArray($typeDefinitionUnformatted, $typeDefinitionIndent));
		$stubFile->replaceTextInStub(PSR4StubFile::TYPE_DEFINITION_DECLARATION, $typeDefinitionFormatted);

		$className = $typeGenerator->getName();
		$stubFile->replaceTextInStub(PSR4StubFile::DUMMY_CLASSNAME, $className);

		$namespace = $this->generateNamespace($baseNamespace);
		$stubFile->replaceTextInStub(PSR4StubFile::DUMMY_NAMESPACE, $namespace);

		if ($this->_formatter->useConstantsForEnums) {
			$variablesDeclarationLine = $stubFile->getVariablesDeclarationLine();
			$variablesDeclarationIndent = $this->_formatter->guessIndentsCount($variablesDeclarationLine);
			$variablesDeclarationNoIndent = $typeGenerator->getConstantsDeclaration();
			$variablesDeclarationIndented = ltrim($this->_formatter->indent($variablesDeclarationNoIndent, $variablesDeclarationIndent));
			$stubFile->replaceTextInStub(PSR4StubFile::VARIABLES_DECLARATION, $variablesDeclarationIndented);
		}
		else {
			$stubFile->replaceTextInStub(PSR4StubFile::VARIABLES_DECLARATION, "");
		}

		$usesDeclarations = $this->getDependenciesUses($usesDependencies);
		$stubFile->replaceTextInStub(PSR4StubFile::USES_DECLARATION, $usesDeclarations);

		// Finds type destination from FQN
		$relevantFQN = str_replace($this->_baseNamespace, "", $classFQN);
		$relevantFQN = str_replace("\\", "/", $relevantFQN);

		// Simply create the namespace + .php extension file
		file_put_contents($this->_targetDir . $relevantFQN . ".php", $stubFile->getContent());
	}

	/**
	 * @param BaseTypeGeneratorInterface $generatorType
	 * @return string
	 */
	protected function getStubFilename($generatorType) {
		switch(get_class($generatorType)) {
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

	protected function getNamespaceSubpart($generatorType) {
		switch(get_class($generatorType)) {
			case Enum::class:
				return "Enum\\";
			case Type::class:
				return "\\";
			case Scalar::class:
				return "Scalar\\";
			case InterfaceDeclaration::class:
				return 'Interface\\';
		}
	}
}