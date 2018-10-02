<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(type="string", length=5)
     * @var string
     */
    private $delegation_code;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @var string
     */
    private $analytic_code;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Project
     */
    public function setCode(string $code): Project
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
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelegationCode(): string
    {
        return $this->delegation_code;
    }

    /**
     * @param string $delegation_code
     * @return Project
     */
    public function setDelegationCode(string $delegation_code): Project
    {
        $this->delegation_code = $delegation_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnalyticCode(): string
    {
        return $this->analytic_code;
    }

    /**
     * @param string $analytic_code
     * @return Project
     */
    public function setAnalyticCode(string $analytic_code): Project
    {
        $this->analytic_code = $analytic_code;
        return $this;
    }
}