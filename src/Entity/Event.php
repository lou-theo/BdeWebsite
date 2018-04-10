<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\Table(name="event")
 */
class Event
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
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250)
     */
    private $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $eventDate;

    /**
     * @var int
     *
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $usersParticipate;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->usersParticipate = new ArrayCollection();
    }

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return \DateTime
     */
    public function getEventDate(): \DateTime
    {
        return $this->eventDate;
    }

    /**
     * @param \DateTime $eventDate
     */
    public function setEventDate(\DateTime $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @param User $user
     */
    public function addUserParticipate(User $user)
    {
        $this->usersParticipate[] = $user;
    }

    public function removeUserParticipate(User $user)
    {
        $this->usersParticipate->removeElement($user);
    }

    /**
     * @return array|ArrayCollection
     */
    public function getUsersParticipate()
    {
        return $this->usersParticipate;
    }
}
