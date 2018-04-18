<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationSender
{
    private $em;
    private $userRepository;

    /**
     * NotificationSender constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->userRepository = $this->em->getRepository(User::class);
    }

    /**
     * @param User $user
     * @param string $message
     */
    public function sendNotificationToUser(User $user, string $message)
    {
        $notification = new Notification();
        $notification->setMessage($message);
        $user->addNotification($notification);

        $this->em->flush();
    }

    /**
     * @param string $role
     * @param string $message
     */
    public function sendNotificationToRole(string $role, string $message)
    {
        $users = $this->userRepository
            ->findAllUserWithRole($role);

        $notification = new Notification();
        $notification->setMessage($message);

        foreach ($users as $user) {
            $user->addNotification($notification);
        }

        $this->em->flush();
    }
}