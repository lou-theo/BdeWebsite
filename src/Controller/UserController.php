<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    /**
     * @Route("/notification", name="notification")
     */
    public function notificationAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $notifications = $user->getNotifications();

        foreach ($notifications as $notification) {
            $notification->setViewed(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $iterator = $notifications->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getId() < $b->getId()) ? -1 : 1;
        });
        $notifications = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('user/notification.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}