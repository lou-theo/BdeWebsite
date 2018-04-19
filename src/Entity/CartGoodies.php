<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartGoodiesRepository")
 * @ORM\Table(name="cart_goodies")
 */
class CartGoodies
{
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
    private $quantity;

    /**
     * @var Cart
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cart")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    /**
     * @var Goodies
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Goodies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $goodies;

    public function __construct()
    {
        $this->quantity = 0;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Cart
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     */
    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }

    /**
     * @return Goodies
     */
    public function getGoodies(): ?Goodies
    {
        return $this->goodies;
    }

    /**
     * @param Goodies $goodies
     */
    public function setGoodies(?Goodies $goodies): void
    {
        $this->goodies = $goodies;
    }
}