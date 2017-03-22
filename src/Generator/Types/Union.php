<?php


namespace GraphQLGen\Generator\Types;


use GraphQLGen\Generator\Types\SubTypes\TypeUsage;

class Union extends BaseTypeGenerator {

	/**
	 * @var TypeUsage[]
	 */
	protected $_types;

	public function __construct($name, $formatter, Array $types, $description = null) {
		$this->setName($name);
		$this->setFormatter($formatter);
		$this->setTypes($types);
		$this->setDescription($description);
	}

	/**
	 * @return string
	 */
	public function generateTypeDefinition() {
		$name = $this->getNameFragment();
		$formattedDescription = $this->getDescriptionFragment($this->getDescription());
        $typesDefinitions = $this->getTypesDefinitionsFragment();
        $resolveType = $this->getResolveTypeFragment();

        $vals = $this->joinArrayFragments([$name, $formattedDescription, $typesDefinitions, $resolveType]);

		return "[ {$vals}  ]";
	}

	/**
	 * @return string[]
	 */
	public function getDependencies() {
		return array_map(function (TypeUsage $type) {
			return $type->getTypeName();
		}, $this->getTypes());
	}

	/**
	 * @return TypeUsage[]
	 */
	public function getTypes() {
		return $this->_types;
	}

	/**
	 * @return string|null
	 */
	public function getVariablesDeclarations() {
		return null;
	}

	/**
	 * @param TypeUsage[] $types
	 */
	public function setTypes($types) {
		$this->_types = $types;
	}

	/**
	 * @return string
	 */
	protected function getTypesDefinitionsFragment() {
		$typesDefinitions = array_map(function (TypeUsage $type) {
			return $this->getFormatter()->getFieldTypeDeclaration($type);
		}, $this->getTypes());

		$typesDefinitionsJoined = $this->joinArrayFragments($typesDefinitions);

		return "'types' => [{$typesDefinitionsJoined}]";
	}

	protected function getResolveTypeFragment() {
        return "'resolveType' => {$this->getFormatter()->getResolveFragmentForUnion()}";
    }
}