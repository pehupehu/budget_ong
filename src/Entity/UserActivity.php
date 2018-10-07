<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users_activities")
 * @ORM\Entity(repositoryClass="App\Repository\UserActivityRepository")
 */
class UserActivity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(name="logged_user_id", type="integer")
     * @var integer
     */
    private $logged_user_id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="logged_user_id", referencedColumnName="id")
     * @var User
     */
    private $loggedUser;

    /**
     * @ORM\Column(name="user_id", type="integer")
     * @var integer
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $libelle;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserActivity
     */
    public function setId(int $id): UserActivity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return UserActivity
     */
    public function setCreatedAt(\DateTime $createdAt): UserActivity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoggedUserId(): int
    {
        return $this->logged_user_id;
    }

    /**
     * @param int $logged_user_id
     * @return UserActivity
     */
    public function setLoggedUserId(int $logged_user_id): UserActivity
    {
        $this->logged_user_id = $logged_user_id;
        return $this;
    }

    /**
     * @return User
     */
    public function getLoggedUser(): User
    {
        return $this->loggedUser;
    }

    /**
     * @param User $loggedUser
     * @return UserActivity
     */
    public function setLoggedUser(User $loggedUser): UserActivity
    {
        $this->loggedUser = $loggedUser;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return UserActivity
     */
    public function setUserId(int $user_id): UserActivity
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserActivity
     */
    public function setUser(User $user): UserActivity
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return UserActivity
     */
    public function setType(string $type): UserActivity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return UserActivity
     */
    public function setLibelle(string $libelle): UserActivity
    {
        $this->libelle = $libelle;
        return $this;
    }
}
