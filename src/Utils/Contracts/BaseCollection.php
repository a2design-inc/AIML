<?php

namespace A2Design\AIML\Utils\Contracts;

use ArrayAccess, ArrayIterator, IteratorAggregate;

abstract class BaseCollection  implements ArrayAccess, IteratorAggregate {
    protected $items = [];
    protected $index = [];

    public function offsetSet($offset, $value)
    {
        $hash = md5($offset);
        $this->items[$hash] = $value;
    }

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

    public function offsetUnset($offset) {
        $hash = md5($offset);
        unset($this->items[$hash]);
        $this->offsetUnsetIndex($offset);

    }

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

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    abstract protected function findElement($offset);
    abstract protected function updateIndex($offset, $indexElement);
    abstract protected function findInIndex($offset);
    abstract protected function offsetUnsetIndex($offset);
}
