<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="delegations")
 * @ORM\Entity(repositoryClass="App\Repository\DelegationRepository")
 * @UniqueEntity("code")
 */
class Delegation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, unique=true)
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Delegation
     */
    public function setId(int $id): Delegation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Delegation
     */
    public function setCode(string $code): Delegation
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Delegation
     */
    public function setName(string $name): Delegation
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s [%s]", $this->getName(), $this->getCode());
    }
    
    public function equal(Delegation $copy) {
        if ($this->getCode() !== $copy->getCode()) {
            return false;
        }
        if ($this->getName() !== $copy->getName()) {
            return false;
        }

        return true;
    }
    
    public function copy(Delegation $copy) {
        $this->setCode($copy->getCode());
        $this->setName($copy->getName());
    }

    public function canBeRemove()
    {
        return true;
    }

    public function remove()
    {
    }

}