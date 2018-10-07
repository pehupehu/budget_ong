<?php

namespace App\EventListener\Entity;

use App\Entity\User;
use App\Entity\UserActivity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Mapping\PostUpdate;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserListener
{
    /** @var array */
    private $logs = array();

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var SessionInterface */
    private $session;

    /**
     * UserListener constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            /** @var User $loggedUser */
            $loggedUser = $this->tokenStorage->getToken()->getUser();

            $entityManager = $args->getEntityManager();
            $uow = $entityManager->getUnitOfWork();
            $changes = $uow->getEntityChangeSet($entity);

            $modified = [];
            foreach ($this->getMonitored() as $key) {
                if (!isset($changes[$key])) {
                    continue;
                }

                // Locale -> change locale session
                if ($key === 'locale' && $loggedUser === $entity) {
                    $this->session->set('_locale', $entity->getLocale());
                }

                // TODO gérer integer/string/etc
                $modified[] = $key . ' ' . $changes[$key][0] . ' to ' . $changes[$key][1];
            }

            if (!empty($modified)) {
                $userActivity = new UserActivity();
                $userActivity->setCreatedAt(new \DateTime());
                $userActivity->setLoggedUserId($loggedUser->getId());
                $userActivity->setLoggedUser($loggedUser);
                $userActivity->setUserId($entity->getId());
                $userActivity->setUser($entity);
                $userActivity->setType('USER_UPDATED');
                $userActivity->setLibelle(implode(', ', $modified));
                $this->logs[] = $userActivity;
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if (!empty($this->logs)) {
            try {
                $entityManager = $args->getEntityManager();
                foreach ($this->logs as $log) {
                    $entityManager->persist($log);
                }
                $entityManager->flush();
            } catch (\Exception $e) {
                // TODO
            }
        }
    }

    private function getMonitored()
    {
        return [
            'firstname',
            'lastname',
            'isActive',
            'role',
            'locale',
        ];
    }
}