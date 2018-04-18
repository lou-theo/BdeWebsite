<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 * @ORM\Table(name="cart")
 */
class Cart
{
    const PURCHASING = 0;
    const WAITING = 1;
    const PAYED = 2;
    const DELIVERED = 3;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $purchaseDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->state = self::PURCHASING;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getState(): ?int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(?int $state): void
    {
        $this->state = $state;
    }

    /**
     * @return \DateTime
     */
    public function getPurchaseDate(): ?\DateTime
    {
        return $this->purchaseDate;
    }

    /**
     * @param \DateTime $purchaseDate
     */
    public function setPurchaseDate(?\DateTime $purchaseDate): void
    {
        $this->purchaseDate = $purchaseDate;
    }
}
