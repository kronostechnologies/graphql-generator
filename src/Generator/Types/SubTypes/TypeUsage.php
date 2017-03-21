<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class TypeUsage {
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
	protected $_inList;
	/**
	 * @var bool
	 */
	protected $_isListNullable;
	/**
	 * @var bool
	 */
	protected $_isTypeNullable;
	/**
	 * @var string
	 */
	protected $_typeName;

	/**
	 * FieldType constructor.
	 * @param string $typeName
	 * @param bool $isTypeNullable
	 * @param bool $inList
	 * @param bool $isListNullable
	 */
	public function __construct($typeName, $isTypeNullable, $inList, $isListNullable) {
		$this->setTypeName($typeName);
		$this->setIsTypeNullable($isTypeNullable);
		$this->setInList($inList);
		$this->setIsListNullable($isListNullable);
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
	protected function setInList($inList) {
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
	protected function setIsListNullable($isListNullable) {
		$this->_isListNullable = $isListNullable;
	}

	/**
	 * @param bool $isTypeNullable
	 */
	protected function setIsTypeNullable($isTypeNullable) {
		$this->_isTypeNullable = $isTypeNullable;
	}
}