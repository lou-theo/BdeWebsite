<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 * @UniqueEntity(
 *     fields={"mail"},
 *     errorPath="mail",
 *     message="Cette adresse e-mail est déjà utilisée."
 * )
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=30)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,60}$/",
     *     match=true,
     *     message="Le mot de passe doit comporter au moins 1 lettre minuscule, 1 lettre majuscule et 1 chiffre."
     * )
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $mail;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @var Notification
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Notification", cascade={"persist"})
     */
    private $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->roles = [];
        $this->roles[] = 'ROLE_USER';
    }

    /**
     * @return null|string|void
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->getMail();
    }

    /**
     * Efface les données sensibles (mdp en clair par ex)
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return mb_strtoupper($this->lastName);
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return null|string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail)
    {
        $this->mail = $mail;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function deleteRole(string $role)
    {
        $key = array_search($role, $this->roles, true);

        if ($key === false) {
            return false;
        }

        unset($this->roles[$key]);

        return true;
    }

    /**
     * @return null|array
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param null|array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param Notification $notification
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications[] = $notification;
    }

    /**
     * @param Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * @return Notification|ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @return null|string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . mb_strtoupper($this->getLastName());
    }
}
