<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(name="photo")
 * @Vich\Uploadable
 */
class Photo
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
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="photo_event", fileNameProperty="fileName")
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $usersLike;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $userOwner;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", cascade={"all"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $event;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="photo", cascade={"all"}, orphanRemoval=true)
     */
    private $comments;

    /**
     * Photo constructor.
     */
    public function __construct() {
        $this->updatedDate = new \DateTime();
        $this->usersLike = new ArrayCollection();
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
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): ?\DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate(\DateTime $updatedDate): void
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @param User $user
     */
    public function addUserLike(User $user)
    {
        $this->usersLike[] = $user;
    }

    public function removeUserLike(User $user)
    {
        $this->usersLike->removeElement($user);
    }

    /**
     * @return array|ArrayCollection
     */
    public function getUsersLike()
    {
        return $this->usersLike;
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
     * @return Event
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
