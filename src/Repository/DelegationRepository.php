<?php

namespace App\Repository;

use App\Entity\Delegation;
use Doctrine\ORM\EntityRepository;

class DelegationRepository extends EntityRepository
{
    private static $delegations_by_code = null;

    public function loadDelegations()
    {
        return $this->createQueryBuilder('d')
            ->getQuery();
    }

    public function getDelegationsByCode()
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
}
