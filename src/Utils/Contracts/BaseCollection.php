<?php

namespace A2Design\AIML\Utils\Contracts;

use ArrayAccess, ArrayIterator, IteratorAggregate, Countable;

abstract class BaseCollection  implements ArrayAccess, IteratorAggregate, Countable {
    protected $items = [];
    protected $index = [];


    /**
     * Returns count of collection elements
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }
    /**
     * Sets collection element to provided offset
     *
     * @param mixed $offset offset of element
     * @param mixed $value  element value
     */
    public function offsetSet($offset, $value)
    {
        $hash = md5($offset);
        $this->items[$hash] = $value;
    }

    /**
     * Checks that collection has elemnt with provided offset
     *
     * @param mixed $offset offset for check
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $hash = md5($offset);
        if (isset($this->items[$hash])) {
            return true;
        }

        $indexElement = $this->findInIndex($offset);
        if (!empty($indexElement)) {
            return true;
        }

        $element = $this->findElement($offset);

        return !empty($element);
    }


    /**
     * Unsets element by provided offset
     *
     * @param mixed $offset offset for unset
     */
    public function offsetUnset($offset) {
        $hash = md5($offset);
        unset($this->items[$hash]);
        $this->offsetUnsetIndex($offset);

    }


    /**
     * Returns element from provided offset
     *
     * @param mixed $offset element offset
     */
    public function offsetGet($offset) {
        $hash = md5($offset);
        if (isset($this->items[$hash])) {
            return $this->items[$hash];
        }

        $indexElement = $this->findInIndex($offset);

        if (!empty($indexElement)) {
            return $indexElement;
        }

        return $this->findElement($offset);
    }


    /**
     * Returns iterator object
     *
     * @return ArrayIterator iterator with collection elements
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    abstract protected function findElement($offset);
    abstract protected function updateIndex($offset, $indexElement);
    abstract protected function findInIndex($offset);
    abstract protected function offsetUnsetIndex($offset);
}
