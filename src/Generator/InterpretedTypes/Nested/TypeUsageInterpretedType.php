<?php


namespace GraphQLGen\Generator\InterpretedTypes\Nested;


class TypeUsageInterpretedType {
	public static $PRIMARY_TYPES_DOCCOMMENTS = [
		'ID' => 'int',
		'Int' => 'int',
		'String' => 'string',
		'Float' => 'float',
		'Boolean' => 'bool',
	];
	public static $PRIMARY_TYPES_MAPPINGS = [
		'ID' => 'Type::id()',
		'Int' => 'Type::int()',
		'String' => 'Type::string()',
		'Float' => 'Type::float()',
		'Boolean' => 'Type::boolean()',
	];

	/**
	 * @var bool
	 */
	protected $_inList = false;
	/**
	 * @var bool
	 */
	protected $_isListNullable = false;
	/**
	 * @var bool
	 */
	protected $_isTypeNullable = false;
	/**
	 * @var string
	 */
	protected $_typeName = "";

	/**
	 * @return string
	 */
	public function getTypeName() {
		return $this->_typeName;
	}

	/**
	 * @param string $typeName
	 */
	public function setTypeName($typeName) {
		$this->_typeName = $typeName;
	}

	/**
	 * @return bool
	 */
	public function isInList() {
		return $this->_inList;
	}

	/**
	 * @param bool $inList
	 */
	public function setInList($inList) {
		$this->_inList = $inList;
	}

	/**
	 * @return bool
	 */
	public function isListNullable() {
		return $this->_isListNullable;
	}

	/**
	 * @return bool
	 */
	public function isPrimaryType() {
		return array_key_exists($this->_typeName, self::$PRIMARY_TYPES_MAPPINGS);
	}

	/**
	 * @return bool
	 */
	public function isTypeNullable() {
		return $this->_isTypeNullable;
	}

	/**
	 * @param bool $isListNullable
	 */
	public function setIsListNullable($isListNullable) {
		$this->_isListNullable = $isListNullable;
	}

	/**
	 * @param bool $isTypeNullable
	 */
	public function setIsTypeNullable($isTypeNullable) {
		$this->_isTypeNullable = $isTypeNullable;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		if(isset(self::$PRIMARY_TYPES_MAPPINGS[$this->_typeName])) {
			$dependencies[] = 'Type';
		}
		else {
			$dependencies[] = $this->_typeName;
		}

		// Is base object nullable?
		if(!$this->_isTypeNullable) {
			$dependencies[] = 'Type';
		}

		// Is in list?
		if($this->_inList) {
			$dependencies[] = 'Type';
		}

		// Is list nullable?
		if($this->_inList && !$this->_isListNullable) {
			$dependencies[] = 'Type';
		}

		return array_unique($dependencies);
	}
}