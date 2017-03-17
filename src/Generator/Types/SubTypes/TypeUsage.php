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
		$this->_typeName = $type_name;
		$this->_isTypeNullable = $is_type_nullable;
		$this->_inList = $in_list;
		$this->_isListNullable = $is_list_nullable;
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
	public function setInList($inList) {
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
	public function setIsTypeNullable($isTypeNullable) {
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
	public function setIsListNullable($isListNullable) {
		$this->_isListNullable = $isListNullable;
	}
}