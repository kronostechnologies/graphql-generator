<?php


namespace GraphQLGen\Generator\Interpreters;


use Closure;
use Generator;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NodeList;

abstract class Interpreter {
	/**
	 * @var Node
	 */
	protected $_astNode;

	/**
	 * @return string|null
	 */
	public function interpretDescription() {
		return $this->_astNode->description;
	}

	/**
	 * @return string
	 */
	public function interpretName() {
		return $this->_astNode->name->value;
	}

	protected function mapFieldsNodes(Closure $callback){
        return iterator_to_array($this->getNodeListMapIterator($callback, $this->_astNode->fields));
    }

    protected function mapInterfacesNodes(Closure $callback){
        return iterator_to_array($this->getNodeListMapIterator($callback, $this->_astNode->interfaces));
    }

    /**
     * @param Closure $callback
     * @param NodeList|array $nodeList
     * @return Generator
     */
	private function getNodeListMapIterator(Closure $callback, $nodeList){
	    if($nodeList instanceof NodeList){
	        foreach($nodeList as $node){
	            yield $callback($node);
            }
        }
    }
}