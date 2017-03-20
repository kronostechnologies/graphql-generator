<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class TypeUsage {
	public static $PRIMARY_TYPES_MAPPINGS = [
		'ID' => 'Type::id()',
		'Int' => 'Type::int()',
		'String' => 'Type::string()',
		'Float' => 'Type::float()',
		'Boolean' => 'Type::boolean()',
	];

	public static $PRIMARY_TYPES_DOCCOMMENTS = [
		'ID' => 'int',
		'Int' => 'int',
		'String' => 'string',
		'Float' => 'float',
		'Boolean' => 'bool',
	];

	/**
	 * @var string
	 */
	protected $_typeName;

	/**
	 * @var bool
	 */
	protected $_inList;

	/**
	 * @var bool
	 */
	protected $_isTypeNullable;

	/**
	 * @var bool
	 */
	protected $_isListNullable;

	/**
	 * FieldType constructor.
	 * @param string $type_name
	 * @param bool $is_type_nullable
	 * @param bool $in_list
	 * @param bool $is_list_nullable
	 */
	public function __construct($type_name, $is_type_nullable, $in_list, $is_list_nullable) {
		$this->setTypeName($type_name);
		$this->setIsTypeNullable($is_type_nullable);
		$this->setInList($in_list);
		$this->setIsListNullable($is_list_nullable);
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
	public function isTypeNullable() {
		return $this->_isTypeNullable;
	}

	/**
	 * @param bool $isTypeNullable
	 */
	protected function setIsTypeNullable($isTypeNullable) {
		$this->_isTypeNullable = $isTypeNullable;
	}

	/**
	 * @return bool
	 */
	public function isListNullable() {
		return $this->_isListNullable;
	}

	/**
	 * @param bool $isListNullable
	 */
	protected function setIsListNullable($isListNullable) {
		$this->_isListNullable = $isListNullable;
	}

	/**
	 * @return bool
	 */
	public function isPrimaryType() {
		return array_key_exists($this->_typeName, self::$PRIMARY_TYPES_MAPPINGS);
	}
}