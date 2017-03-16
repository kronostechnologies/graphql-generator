<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class Enum implements BaseTypeGeneratorInterface {
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var EnumValue[]
	 */
	public $values;

	/**
	 * @var string|null
	 */
	public $description;

	/**
	 * @var StubFormatter
	 */
	public $formatter;

	const ENUM_VAL_PREFIX = 'VAL_';

	/**
	 * EnumType constructor.
	 * @param string $name
	 * @param EnumValue[] $values
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, Array $values, StubFormatter $formatter, $description = null) {
		$this->formatter = $formatter;
		$this->name = $name;
		$this->values = $values;
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
	    $name = "'name' => '{$this->name}'";
		$formattedDescription = $this->formatter->standardizeDescription($this->description);
		$values = "'values' => [" . $this->getConstantValuesArray() . "]";

		$commaSplitVals = [$name, $formattedDescription, $values];
		$commaSplitVals = array_filter($commaSplitVals);

		$vals = implode(",", $commaSplitVals);

		return "[{$vals}]";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarations() {
        if ($this->formatter->optimizeEnums) {
            return $this->getVariablesDeclarationsOptimized();
        }
        else {
            return $this->getVariablesDeclarationsStandard();
        }
	}

    /**
     * @return string
     */
	protected function getVariablesDeclarationsOptimized() {
        $constants = "";
        $i = 1;
        foreach($this->values as $value) {
            $constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->name} = {$i};\n";
            $i++;
        }

        return $constants;
    }

    /**
     * @return string
     */
    protected function getVariablesDeclarationsStandard() {
        $constants = "";
        foreach($this->values as $value) {
            $constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->name} = '{$value->name}';\n";
        }

        return $constants;
    }

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}

	protected function getConstantValuesArray() {
		$valuesNames = array_map(function ($value) {
			return $this->getSingleConstantValueEntry($value);
		}, $this->values ?: []);

		return implode("", $valuesNames);
	}

	/**
	 * @param EnumValue $value
	 * @return string
	 */
	protected function getSingleConstantValueEntry($value) {
		$formattedDescription = $this->formatter->standardizeDescription($value->description);

        return "'{$value->name}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$value->name}, {$formattedDescription} ],";
	}
}