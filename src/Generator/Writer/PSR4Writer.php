<?php


namespace GraphQLGen\Generator\Writer;


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
	 * @param string $classFQN
	 * @param string $classContent
	 */
	public function writeClass($classFQN, $classContent) {
		// Finds type destination from FQN
		$relevantFQN = str_replace($this->_baseNamespace, "", $classFQN);
		$relevantFQN = str_replace("\\", "/", $relevantFQN);

		// Simply create the namespace + .php extension file
		file_put_contents($this->_targetDir . $relevantFQN . ".php", $classContent);
	}
}