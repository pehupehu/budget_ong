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
     * @var string
     */
    private $sort;

    /**
     * @var string
     */
    private $order;

    /**
     * Pager constructor.
     * @param Query|QueryBuilder $query
     * @param bool $fetchJoinCollection
     */
    public function __construct($query, $fetchJoinCollection = true, $nb_by_pages = 15, $page = 1)
    {
        $this->query = $query;
        $this->paginator = new Paginator($query, $fetchJoinCollection);
        $this->count = $this->paginator->count();
        $this->nb_by_pages = $nb_by_pages;
        $this->page = $page;
        $this->nb_pages = ceil($this->count/$this->nb_by_pages);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        $this->query
            ->setFirstResult(($this->getPage() - 1) * $this->getNbByPages())
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
     * @param float|int $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getNbByPages(): int
    {
        return $this->nb_by_pages;
    }

    /**
     * @param int $nb_by_pages
     */
    public function setNbByPages(int $nb_by_pages): void
    {
        $this->nb_by_pages = $nb_by_pages;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getNbPages(): int
    {
        return $this->nb_pages;
    }

    /**
     * @param int $nb_pages
     */
    public function setNbPages(int $nb_pages): void
    {
        $this->nb_pages = $nb_pages;
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
     */
    public function setRouteName(string $route_name): void
    {
        $this->route_name = $route_name;
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
     */
    public function setRouteParams(array $route_params): void
    {
        $this->route_params = $route_params;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return Pager
     */
    public function setSort(string $sort): Pager
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return Pager
     */
    public function setOrder(string $order): Pager
    {
        $this->order = $order;
        return $this;
    }
}