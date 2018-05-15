<?php


namespace GraphQLGen\Generator\Writer\Namespaced\Classes\ContentCreator;


use function get_class;
use GraphQLGen\Generator\FragmentGenerators\FragmentGeneratorInterface;
use GraphQLGen\Generator\FragmentGenerators\Main\InputFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\InterfaceFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\ScalarFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\TypeDeclarationFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\Main\UnionFragmentGenerator;
use GraphQLGen\Generator\FragmentGenerators\VariablesDefiningGeneratorInterface;
use GraphQLGen\Generator\Writer\Namespaced\ClassComposer;
use GraphQLGen\Generator\Writer\Namespaced\Classes\ObjectType;

class ObjectTypeContent extends BaseContentCreator {
	/**
	 * @var ObjectType
	 */
	protected $_objectTypeClass;

	/**
	 * @var FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	protected $_fragmentGenerator;

    /**
     * @var bool
     */
	protected $_useInstancedTypeStore;

    /**
     * @param bool $useInstancedTypeStore
     */
	public function __construct($useInstancedTypeStore = false) {
        $this->_useInstancedTypeStore = $useInstancedTypeStore;
    }

    /**
	 * @return ObjectType
	 */
	public function getObjectTypeClass() {
		return $this->_objectTypeClass;
	}

	/**
	 * @param ObjectType $objectTypeClass
	 */
	public function setObjectTypeClass($objectTypeClass) {
		$this->_objectTypeClass = $objectTypeClass;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$contentAsLines = [];
		$resolverCreationFragment = sprintf(ClassComposer::RESOLVER_FACTORY_CREATION, $this->getFragmentGenerator()->getName());

		if ($this->_useInstancedTypeStore) {
		    $contentAsLines[] = "public function __construct(AutomatedTypeRegistry \$typeRegistry, Resolver \$queryResolver) {";

		    if (get_class($this->getFragmentGenerator()) === ScalarFragmentGenerator::class) {
				$contentAsLines[] = " \$this->resolver = \$queryResolver;";
			}
        } else {
            if ($this->isResolverNecessary()) {
                $contentAsLines[] = "public function __construct(\$resolverFactory) {";
                $contentAsLines[] = " \$this->resolver = {$resolverCreationFragment};";
            } else {
                $contentAsLines[] = "public function __construct() {";
            }
        }

		if (get_class($this->getFragmentGenerator()) == ScalarFragmentGenerator::class) {
			$contentAsLines[] = 'parent::__construct();';
			$contentAsLines[] = str_replace("{{TypeName}}", $this->getFragmentGenerator()->getName(), $this->getFragmentGenerator()->generateTypeDefinition());;
		} else {
			$contentAsLines[] = "parent::__construct(";
			$contentAsLines[] = str_replace("{{TypeName}}", $this->getFragmentGenerator()->getName(), $this->getFragmentGenerator()->generateTypeDefinition());
			$contentAsLines[] = ");";
		}

		$contentAsLines[] = "}";

		return implode(PHP_EOL, $contentAsLines);
	}

	/**
	 * @return string
	 */
	public function getVariables() {
		$variableDeclarationsAsLines = [];

		if ($this->isResolverNecessary()) {
			$variableDeclarationsAsLines[] = "public \$resolver;";
		}

		if ($this->getFragmentGenerator() instanceof VariablesDefiningGeneratorInterface) {
			$variableDeclarationsAsLines[] = $this->getFragmentGenerator()->getVariablesDeclarations();
		}


		return implode(PHP_EOL, $variableDeclarationsAsLines);
	}

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->getObjectTypeClass()->getNamespace();
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->getObjectTypeClass()->getClassName();
	}

	/**
	 * @return FragmentGeneratorInterface|VariablesDefiningGeneratorInterface
	 */
	public function getFragmentGenerator() {
		return $this->_fragmentGenerator;
	}

	/**
	 * @param FragmentGeneratorInterface|VariablesDefiningGeneratorInterface $fragmentGenerator
	 */
	public function setFragmentGenerator($fragmentGenerator) {
		$this->_fragmentGenerator = $fragmentGenerator;
	}

	/**
	 * @return string
	 */
	public function getParentClassName() {
		return $this->getObjectTypeClass()->getParentClassName();
	}

	protected function isResolverNecessary() {
		$retVal =
			!$this->_useInstancedTypeStore &&
			in_array(get_class($this->getFragmentGenerator()), [InterfaceFragmentGenerator::class, TypeDeclarationFragmentGenerator::class, InputFragmentGenerator::class, UnionFragmentGenerator::class, ScalarFragmentGenerator::class]);

		$retVal = ($retVal || get_class($this->getFragmentGenerator()) === ScalarFragmentGenerator::class);

		return $retVal;
	}
}
