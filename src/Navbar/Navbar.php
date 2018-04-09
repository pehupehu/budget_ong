<?php

namespace App\Navbar;

/**
 * Class Navbar
 * @package App\Navbar
 */
class Navbar implements \Iterator
{
    /** @var int */
    private $position = 0;

    /** @var array */
    private $array = [];

    /**
     * Navbar constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    /**
     * @param $child
     */
    public function add($child)
    {
        $this->array[$this->position++] = $child;
    }
}