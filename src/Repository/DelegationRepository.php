<?php

namespace App\Repository;

use App\Entity\Delegation;
use Doctrine\ORM\EntityRepository;

/**
 * Class DelegationRepository
 * @package App\Repository
 */
class DelegationRepository extends EntityRepository
{
    private static $delegations_by_code;

    /**
     * @param array $filters
     * @param null $sort
     * @param string $order
     * @return \Doctrine\ORM\Query
     */
    public function loadDelegations(array $filters = [], $sort = null, string $order = 'asc')
    {
        $query = $this->createQueryBuilder('d');

        if (!empty($filters['id'])) {
            $query->where('d.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (!empty($filters['ids'])) {
            $query->where('d.id IN (:ids)')
                ->setParameter('ids', $filters['ids']);
        }

        if (!empty($filters['code'])) {
            $query->where('d.code LIKE :code')
                ->setParameter('code', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['name'])) {
            $query->where('d.name LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if ($sort) {
            $query->orderBy("d.$sort", $order);
        }

        return $query->getQuery();
    }

    /**
     * @return array
     */
    public function getDelegationsByCode(): array
    {
        if (self::$delegations_by_code !== null) {
            return self::$delegations_by_code;
        }

        self::$delegations_by_code = [];
        
        $query = $this->loadDelegations();
        /** @var Delegation $delegation */
        foreach ($query->execute() as $delegation) {
            self::$delegations_by_code[$delegation->getCode()] = $delegation;
        }

        return self::$delegations_by_code;
    }

    /**
     * @param string $sort
     * @param string $default
     * @return string
     */
    public static function checkSort(string $sort, string $default = 'code'): string
    {
        if (!in_array($sort, ['code', 'name'])) {
            return $default;
        }

        return $sort;
    }
}
