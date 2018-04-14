<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $udatedDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Photo
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Photo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    public function __construct()
    {
        $this->udatedDate = new \DateTime();
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return \DateTime
     */
    public function getUdatedDate(): \DateTime
    {
        return $this->udatedDate;
    }

    /**
     * @param \DateTime $udatedDate
     */
    public function setUdatedDate(\DateTime $udatedDate): void
    {
        $this->udatedDate = $udatedDate;
    }

    /**
     * @return User
     */
    public function getUser(): User
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
     * @return Photo
     */
    public function getPhoto(): Photo
    {
        return $this->photo;
    }

    /**
     * @param Photo $photo
     */
    public function setPhoto(Photo $photo): void
    {
        $this->photo = $photo;
    }
}
