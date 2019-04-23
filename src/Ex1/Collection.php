<?php

namespace Socloz\Recruitment\Ex1;

use \IteratorAggregate;

class Collection implements IteratorAggregate
{
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function get(int $key)
    {
        if ($this->hasKey($key)) return $this->items[$key];

        return false;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function hasKey(int $key)
    {
        return array_key_exists($key, $this->items);
    }

    public function set(int $key, $value)
    {
        $this->items[$key] = $value;
    }

    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}

?>