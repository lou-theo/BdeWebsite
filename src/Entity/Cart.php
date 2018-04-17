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
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $bought;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Goodies", cascade={"persist"})
     */
    private $goodiesList;

    public function __construct()
    {
        $this->goodiesList = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isBought(): ?bool
    {
        return $this->bought;
    }

    /**
     * @param bool $bought
     */
    public function setBought(bool $bought): void
    {
        $this->bought = $bought;
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
     * @param Goodies $goodies
     */
    public function addGoodies(Goodies $goodies)
    {
        $this->goodiesList[] = $goodies;
    }

    /**
     * @param Goodies $goodies
     */
    public function removeGoodies(Goodies $goodies)
    {
        $this->goodiesList->removeElement($goodies);
    }

    /**
     * @return array|ArrayCollection
     */
    public function getGoodiesList()
    {
        return $this->goodiesList;
    }
}
