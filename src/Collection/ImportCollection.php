<?php

namespace App\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ImportCollection
 * @package App\Collection
 */
class ImportCollection
{
    /**
     * @var ImportObject[]||ArrayCollection
     */
    protected $objects;

    /**
     * ImportCollection constructor.
     */
    public function __construct()
    {
        $this->objects = new ArrayCollection();
    }

    /**
     * @return ImportObject[]|ArrayCollection
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param $object
     * @param $match
     */
    public function add($object, $match, $resolve = null)
    {
        $importObject = new ImportObject($object, $match, $resolve);
        $this->getObjects()->add($importObject);
    }
}

/**
 * Class ImportObject
 * @package App\Collection
 */
class ImportObject implements \ArrayAccess
{
    private $container = [];

    public function __construct($import, $match, $resolve = null)
    {
        $this->container = [
            'import' => $import,
            'match' => $match,
            'resolve' => $resolve,
        ];
    }

    public function getImport()
    {
        return $this->container['import'];
    }

    public function getMatch()
    {
        return $this->container['match'];
    }

    public function getResolve()
    {
        return $this->container['resolve'];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}