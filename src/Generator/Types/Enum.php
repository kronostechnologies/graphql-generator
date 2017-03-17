<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class Enum extends BaseTypeGenerator {
	/**
	 * @var EnumValue[]
	 */
	protected $_values;


	const ENUM_VAL_PREFIX = 'VAL_';

	/**
	 * EnumType constructor.
	 * @param string $name
	 * @param EnumValue[] $values
	 * @param StubFormatter $formatter
	 * @param string|null $description
	 */
	public function __construct($name, Array $values, StubFormatter $formatter, $description = null) {
		$this->_formatter = $formatter;
		$this->_name = $name;
		$this->_values = $values;
		$this->_description = $description;
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
	    $nameFragment = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->getDescription());
		$valuesFragment = $this->getValuesFragment();

		$vals = $this->joinArrayFragments([$nameFragment, $formattedDescription, $valuesFragment]);

		return "[{$vals}]";
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getVariablesDeclarations() {
        if ($this->_formatter->optimizeEnums) {
            return $this->getVariablesDeclarationsOptimized();
        }
        else {
            return $this->getVariablesDeclarationsStandard();
        }
	}

	/**
	 * @return EnumValue[]
	 */
	public function getValues() {
		return $this->_values;
	}

	/**
	 * @param EnumValue[] $_values
	 */
	public function setValues($_values) {
		$this->_values = $_values;
	}

	/**
     * @return string
     */
	protected function getVariablesDeclarationsOptimized() {
        $constants = "";
        $i = 1;
        foreach($this->getValues() as $value) {
            $constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->getName()} = {$i};\n";
            $i++;
        }

        return $constants;
    }

    /**
     * @return string
     */
    protected function getVariablesDeclarationsStandard() {
        $constants = "";
        foreach($this->getValues() as $value) {
            $constants .= "const " . self::ENUM_VAL_PREFIX . "{$value->getName()} = '{$value->getName()}';\n";
        }

        return $constants;
    }

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}

	/**
	 * @return string
	 */
	protected function getConstantValuesArray() {
		$valuesNames = array_map(function ($value) {
			return $this->getSingleConstantValueEntry($value);
		}, $this->getValues());

		return implode("", $valuesNames);
	}

	/**
	 * @param EnumValue $value
	 * @return string
	 */
	protected function getSingleConstantValueEntry($value) {
		$formattedDescription = $this->getDescriptionFragment($value->getDescription());

        return "'{$value->getName()}' => [ 'value' => self::" . self::ENUM_VAL_PREFIX . "{$value->getName()}, {$formattedDescription} ],";
	}

	/**
	 * @return string
	 */
	protected function getValuesFragment() {
		return "'values' => [" . $this->getConstantValuesArray() . "]";
	}
}