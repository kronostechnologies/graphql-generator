<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Formatters\StubFormatter;
use GraphQLGen\Generator\Types\SubTypes\EnumValue;

class Enum extends BaseTypeGenerator {
	const ENUM_VAL_PREFIX = 'VAL_';
	/**
	 * @var EnumValue[]
	 */
	protected $_values;

	/**
	 * EnumType constructor.
	 * @param string $name
	 * @param StubFormatter $formatter
	 * @param EnumValue[] $values
	 * @param string|null $description
	 */
	public function __construct($name, StubFormatter $formatter, Array $values, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setValues($values);
		$this->setDescription($description);
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
	 * @return string[]
	 */
	public function getDependencies() {
		return [];
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
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
	public function getVariablesDeclarations() {
        if ($this->_formatter->optimizeEnums) {
            return $this->getVariablesDeclarationsOptimized();
        }
        else {
            return $this->getVariablesDeclarationsStandard();
        }
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
}