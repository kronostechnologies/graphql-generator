<?php


namespace GraphQLGen\Generator\Types\SubTypes;


class FieldType {
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
	 * @return string
	 */
	public function getFieldTypeDeclaration() {
		// Primary type check
		if (in_array($this->typeName, self::$PRIMARY_TYPES_MAPPINGS)) {
			$typeDeclaration = self::$PRIMARY_TYPES_MAPPINGS[$this->typeName];
		}
		else {
			$typeDeclaration = 'TypeStore::get' . $this->typeName . '()';
		}

		// Is base object nullable?
		if(!$this->isTypeNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		// Is in list?
		if($this->inList) {
			$typeDeclaration = 'Type::listOf(' . $typeDeclaration . ')';
		}

		// Is list nullable?
		if($this->inList && !$this->isListNullable) {
			$typeDeclaration = 'Type::nonNull(' . $typeDeclaration . ')';
		}

		return $typeDeclaration;
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		$dependencies = [];

		if (in_array($this->typeName, self::$PRIMARY_TYPES_MAPPINGS)) {
			$dependencies[] = 'Type';
		}
		else {
			$dependencies[] = 'TypeStore';
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