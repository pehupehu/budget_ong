<?php

namespace App\Repository;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param string $username
     * @return mixed|null|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.isActive = :isActive')
            ->setParameter('username', $username)
            ->setParameter('isActive', User::ACTIVE)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param array $filters
     * @return \Doctrine\ORM\Query
     */
    public function loadUsers($filters = [])
    {
        $query = $this->createQueryBuilder('u');

        if (!empty($filters['id'])) {
            $query->where('u.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (!empty($filters['ids'])) {
            $query->where('u.id IN (:ids)')
                ->setParameter('ids', $filters['ids']);
        }

        if (!empty($filters['firstname'])) {
            $query->where('u.firstname LIKE :firstname')
                ->setParameter('firstname', '%' . $filters['firstname'] . '%');
        }

        if (!empty($filters['lastname'])) {
            $query->where('u.lastname LIKE :lastname')
                ->setParameter('lastname', '%' . $filters['lastname'] . '%');
        }

        if (!empty($filters['username'])) {
            $query->where('u.username LIKE :username')
                ->setParameter('username', '%' . $filters['username'] . '%');
        }

        if (!empty($filters['role'])) {
            $query->where('u.role = :role')
                ->setParameter('role', $filters['role']);
        }

        return $query->getQuery();
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return [
            strtolower(User::ROLE_ADMIN) => User::ROLE_ADMIN,
            strtolower(User::ROLE_USER) => User::ROLE_USER,
        ];
    }
}
