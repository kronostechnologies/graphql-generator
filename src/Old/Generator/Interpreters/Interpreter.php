<?php


namespace GraphQLGen\Old\Generator\Interpreters;


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

    /**
     * @param Closure $callback
     * @param NodeList|array $nodeList
     * @return array
     */
    protected function mapNodeList(Closure $callback, $nodeList){
        return iterator_to_array($this->getNodeListMapIterator($callback, $nodeList));
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