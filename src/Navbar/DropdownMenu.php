<?php

namespace App\Navbar;

/**
 * Class DropdownMenu
 * @package App\Navbar
 */
class DropdownMenu extends GenericItem implements \Iterator
{
    /** @var int */
    private $position = 0;

    /** @var array */
    private $array = [];

    /**
     * DropdownMenu constructor.
     * @param $translation_key
     * @param $route
     * @param string $icon
     * @param bool $is_active
     */
    public function __construct($translation_key, $route = '', $icon = '', $is_active = false)
    {
        parent::__construct($translation_key, $route, $icon, $is_active);
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
    
    public function hasChildren()
    {
        return count($this->array) > 0;
    }
    
    public function isDropdown()
    {
        return true;
    }
}