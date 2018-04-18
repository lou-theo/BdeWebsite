<?php

namespace App\Controller\Events;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Photo;
use App\Service\NotificationSender;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ModerationController extends Controller
{
    /**
     * @param int $idPhoto
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/moderation/delete/photo/{idPhoto}", name="moderation_photo_remove", methods="GET", requirements={"idPhoto" = "\d+"})
     */
    public function ajaxModerationPhotoRemove(int $idPhoto): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_BDE');

        $em = $this->getDoctrine()->getManager();

        $photo = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findOneBy(['id' => $idPhoto]);

        if (!$photo) {
            return $this->json(['status' => 'error', 'message' => 'Aucune photo associe a l url']);
        }

        $em->remove($photo);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'L image a correctement ete supprimee']);
    }

    /**
     * @param int $idComment
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/moderation/delete/comment/{idComment}", name="moderation_comment_remove", methods="GET", requirements={"idComment" = "\d+"})
     */
    public function ajaxModerationCommentRemove(int $idComment): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_BDE');

        $em = $this->getDoctrine()->getManager();

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findOneBy(['id' => $idComment]);

        if (!$comment) {
            return $this->json(['status' => 'error', 'message' => 'Aucun commentaire associe a l url']);
        }

        $em->remove($comment);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'Le commentaire a correctement ete supprime']);
    }

    /**
     * @param int $idEvent
     * @param NotificationSender $notificationSender
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/moderation/warn/event/{idEvent}", name="moderation_event_warn", methods="GET", requirements={"idEvent" = "\d+"})
     */
    public function ajaxModerationEventWarn(int $idEvent, NotificationSender $notificationSender): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_CESI');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$event) {
            return $this->json(['status' => 'error', 'message' => 'Aucun evenement associe a l url']);
        }

        $notificationSender->sendNotificationToRole('ROLE_BDE', 'L\'évènement du nom de "' . $event->getTitle() . '" id('. $event->getId() . ') a été considéré comme offensant et/ou indésirable. Le lien permettant de s\'y rendre est le suivant : ' . $this->generateUrl('future_event_details', ['idEvent' => $idEvent], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->json(['status' => 'success', 'message' => 'L evenement a correctement ete signale aux membres du BDE']);
    }

    /**
     * @param int $idPhoto
     * @param NotificationSender $notificationSender
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/moderation/warn/photo/{idPhoto}", name="moderation_photo_warn", methods="GET", requirements={"idPhoto" = "\d+"})
     */
    public function ajaxModerationPhotoWarn(int $idPhoto, NotificationSender $notificationSender): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_CESI');

        $photo = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findOneBy(['id' => $idPhoto]);

        if (!$photo) {
            return $this->json(['status' => 'error', 'message' => 'Aucune photo associe a l url']);
        }

        $notificationSender->sendNotificationToRole('ROLE_BDE', 'La photo du nom de "' . $photo->getTitle() . '" id('. $photo->getId() . ') a été considéré comme offensante et/ou indésirable. Le lien permettant de s\'y rendre est le suivant : ' . $this->generateUrl('past_event_details', ['idEvent' => $photo->getEvent()->getId() ], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->json(['status' => 'success', 'message' => 'La photo a correctement ete signale aux membres du BDE']);
    }

    /**
     * @param int $idComment
     * @param NotificationSender $notificationSender
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/moderation/warn/comment/{idComment}", name="moderation_comment_warn", methods="GET", requirements={"idComment" = "\d+"})
     */
    public function ajaxModerationCommentWarn(int $idComment, NotificationSender $notificationSender): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_CESI');

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findOneBy(['id' => $idComment]);

        if (!$comment) {
            return $this->json(['status' => 'error', 'message' => 'Aucun commentaire associe a l url']);
        }

        $notificationSender->sendNotificationToRole('ROLE_BDE', 'La commentaire (id '. $comment->getId() . ') dont le message est "' . $comment->getMessage() . '"  a été considéré comme offensante et/ou indésirable. Le lien permettant de s\'y rendre est le suivant : ' . $this->generateUrl('past_event_details', ['idEvent' => $comment->getPhoto()->getEvent()->getId() ], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->json(['status' => 'success', 'message' => 'Le commentaire a correctement ete signale aux membres du BDE']);
    }
}
