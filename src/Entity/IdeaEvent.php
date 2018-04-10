<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdeaEventRepository")
 * @ORM\Table(name="idea_event")
 */
class IdeaEvent
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userOwner;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $usersVote;

    public function __construct()
    {
        $this->usersVote = new ArrayCollection();
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
     * @return User
     */
    public function getUserOwner(): User
    {
        return $this->userOwner;
    }

    /**
     * @param User $userOwner
     */
    public function setUserOwner(User $userOwner): void
    {
        $this->userOwner = $userOwner;
    }

    /**
     * @param User $user
     */
    public function addUserVote(User $user)
    {
        $this->usersVote[] = $user;
    }

    /**
     * @param User $user
     */
    public function removeUserVote(User $user)
    {
        $this->usersVote->removeElement($user);
    }

    /**
     * @return array|ArrayCollection
     */
    public function getUsersVote()
    {
        return $this->usersVote;
    }
}
