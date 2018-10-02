<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $delegation_id;

    /**
     * @ORM\ManyToOne(targetEntity="Delegation")
     * @ORM\JoinColumn(name="delegation_id", referencedColumnName="id")
     */
    private $delegation;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $category_id;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $compte_comptable;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $info_comptable;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $debit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @var float
     */
    private $credit;

    /**
     * @ORM\Column(type="string", length=5)
     * @var string
     */
    private $not_use;

    /**
     * @ORM\Column(type="string", length=25)
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
     * @param int $id
     * @return Transaction
     */
    public function setId(int $id): Transaction
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelegationId(): int
    {
        return $this->delegation_id;
    }

    /**
     * @param int $delegation_id
     * @return Transaction
     */
    public function setDelegationId(int $delegation_id): Transaction
    {
        $this->delegation_id = $delegation_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelegation()
    {
        return $this->delegation;
    }

    /**
     * @param mixed $delegation
     * @return Transaction
     */
    public function setDelegation($delegation)
    {
        $this->delegation = $delegation;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate(\DateTime $date): Transaction
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * @return Transaction
     */
    public function setCategoryId(int $category_id): Transaction
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Transaction
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompteComptable(): string
    {
        return $this->compte_comptable;
    }

    /**
     * @param string $compte_comptable
     * @return Transaction
     */
    public function setCompteComptable(string $compte_comptable): Transaction
    {
        $this->compte_comptable = $compte_comptable;
        return $this;
    }

    /**
     * @return string
     */
    public function getInfoComptable(): string
    {
        return $this->info_comptable;
    }

    /**
     * @param string $info_comptable
     * @return Transaction
     */
    public function setInfoComptable(string $info_comptable): Transaction
    {
        $this->info_comptable = $info_comptable;
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
     * @return Transaction
     */
    public function setName(string $name): Transaction
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getDebit(): float
    {
        return $this->debit;
    }

    /**
     * @param float $debit
     * @return Transaction
     */
    public function setDebit(float $debit): Transaction
    {
        $this->debit = $debit;
        return $this;
    }

    /**
     * @return float
     */
    public function getCredit(): float
    {
        return $this->credit;
    }

    /**
     * @param float $credit
     * @return Transaction
     */
    public function setCredit(float $credit): Transaction
    {
        $this->credit = $credit;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotUse(): string
    {
        return $this->not_use;
    }

    /**
     * @param string $not_use
     * @return Transaction
     */
    public function setNotUse(string $not_use): Transaction
    {
        $this->not_use = $not_use;
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
     * @return Transaction
     */
    public function setAnalyticCode(string $analytic_code): Transaction
    {
        $this->analytic_code = $analytic_code;
        return $this;
    }
    
}