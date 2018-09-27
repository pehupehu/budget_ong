<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class DelegationRepository extends EntityRepository
{
    public function loadDelegations()
    {
        return $this->createQueryBuilder('d')
            ->getQuery();
    }
}
