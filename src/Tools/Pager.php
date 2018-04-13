<?php

namespace App\Tools;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class Pager
 * @package App\Tools
 */
class Pager
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var float|int
     */
    private $count;

    /**
     * @var int
     */
    private $nb_by_pages;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $nb_pages;

    /**
     * @var string
     */
    private $route_name;

    /**
     * @var array
     */
    private $route_params;

    /**
     * Pager constructor.
     * @param Query|QueryBuilder $query
     * @param bool $fetchJoinCollection
     */
    public function __construct($query, $fetchJoinCollection = true, $nb_by_pages = 2, $page = 1)
    {
        $this->query = $query;
        $this->paginator = new Paginator($query, $fetchJoinCollection);
        $this->count = $this->paginator->count();
        $this->nb_by_pages = $nb_by_pages;
        $this->page = $page;
        $this->nb_pages = ceil($this->count/$this->nb_by_pages);

        dump($this->count);
        dump($this->nb_by_pages);
        dump($this->page);
        dump($this->nb_pages);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        $this->query
            ->setFirstResult($this->getPage() * $this->getNbByPages())
            ->setMaxResults($this->getNbByPages());

        return $this->paginator->getIterator();
    }

    /**
     * @return float|int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getNbByPages(): int
    {
        return $this->nb_by_pages;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getNbPages(): int
    {
        return $this->nb_pages;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->route_name;
    }

    /**
     * @param string $route_name
     * @return Pager
     */
    public function setRouteName(string $route_name): Pager
    {
        $this->route_name = $route_name;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->route_params;
    }

    /**
     * @param array $route_params
     * @return Pager
     */
    public function setRouteParams(array $route_params): Pager
    {
        $this->route_params = $route_params;
        return $this;
    }
}