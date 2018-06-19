<?php


namespace GraphQLGen\Definitions;


abstract class AstParsedClassDefinition
{
    const DEFINITION_SUFFIX = 'Definition';
    const DTO_SUFFIX = 'DTO';

    /**
     * @var string[]
     */
    protected $typeDependencies;

    /**
     * @var string
     */
    protected $typeUniqueName;

    /**
     * AstParsedClassDefinition constructor.
     * @param string $typeUniqueName
     */
    public function __construct($typeUniqueName)
    {
        $this->typeUniqueName = $typeUniqueName;
    }

    /**
     * @return string[]
     */
    public function getTypeDependencies()
    {
        return $this->typeDependencies;
    }

    /**
     * @return string
     */
    public function getDtoClassName()
    {
        return $this->typeUniqueName . self::DTO_SUFFIX;
    }

    /**
     * @return string
     */
    public function getDefinitionClassName()
    {
        return $this->typeUniqueName . self::DEFINITION_SUFFIX;
    }
}