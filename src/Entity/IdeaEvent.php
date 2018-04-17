<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdeaEventRepository")
 * @ORM\Table(name="idea_event")
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="event", fileNameProperty="picture")
     */
    private $pictureFile;

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
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
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
    public function getDescription(): ?string
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
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param null|string $picture
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return File
     */
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * @param File $pictureFile
     */
    public function setPictureFile(File $pictureFile): void
    {
        $this->pictureFile = $pictureFile;
    }

    /**
     * @return User
     */
    public function getUserOwner(): ?User
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
    public function addUserVote(User $user): void
    {
        $this->usersVote[] = $user;
    }

    /**
     * @param User $user
     */
    public function removeUserVote(User $user): void
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
