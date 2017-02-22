<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class TypeUsage {
	public static $PRIMARY_TYPES_MAPPINGS = [
		'Int' => 'Type::int()',
		'String' => 'Type::string()',
		'Float' => 'Type::float()',
		'Bool' => 'Type::bool()',
	];

	/**
	 * @var string
	 */
	public $typeName;

	/**
	 * @var bool
	 */
	public $inList;

	/**
	 * @var bool
	 */
	public $isTypeNullable;

	/**
	 * @var bool
	 */
	public $isListNullable;

	/**
	 * FieldType constructor.
	 * @param string $type_name
	 * @param bool $is_type_nullable
	 * @param bool $in_list
	 * @param bool $is_list_nullable
	 */
	public function __construct($type_name, $is_type_nullable, $in_list, $is_list_nullable) {
		$this->typeName = $type_name;
		$this->isTypeNullable = $is_type_nullable;
		$this->inList = $in_list;
		$this->isListNullable = $is_list_nullable;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		if (isset(self::$PRIMARY_TYPES_MAPPINGS[$this->typeName])) {
			$dependencies[] = 'Type';
		}
		else {
			$dependencies[] = $this->typeName;
		}

		// Is base object nullable?
		if(!$this->isTypeNullable) {
			$dependencies[] = 'Type';
		}

		// Is in list?
		if($this->inList) {
			$dependencies[] = 'Type';
		}

		// Is list nullable?
		if($this->inList && !$this->isListNullable) {
			$dependencies[] = 'Type';
		}

		return array_unique($dependencies);
	}
}