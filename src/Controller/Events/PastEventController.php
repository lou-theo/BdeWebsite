<?php

namespace App\Controller\Events;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Photo;
use App\Form\CommentForm;
use App\Form\PhotoForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PastEventController extends Controller
{
    /**
     * @Route("/evenements/passes", name="past_event")
     */
    public function pastEventAction()
    {
        $pastEventList = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAllPastEvent();

        $user = $this->getUser();

        return $this->render('event/past_event.html.twig', [
            'pastEventList' => $pastEventList,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int $idEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/passes/{idEvent}", name="past_event_details", requirements={"idEvent" = "\d+"})
     */
    public function pastEventDetailsAction(Request $request, int $idEvent)
    {
        $pastEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$pastEvent) {
            throw $this->createNotFoundException('Aucun évènement associé à l\'url');
        }

        if ($pastEvent->getEventDate() >= new \DateTime("now")) {
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['event' => $pastEvent]);

        $photo = new Photo();
        $photoForm = $this->createForm(PhotoForm::class, $photo);
        $photoForm->handleRequest($request);

        if ($photoForm->isSubmitted() && $photoForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
            $photo->setUserOwner($this->getUser());
            $photo->setEvent($pastEvent);

            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

            $commentData = $commentForm->getData();
            $comment = new Comment();

            $photoCommented = $this->getDoctrine()
                ->getRepository(Photo::class)
                ->findOneBy(['id' => $commentData['idPhoto']]);

            $comment->setMessage($commentData['message']);
            $comment->setPhoto($photoCommented);
            $comment->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $user = $this->getUser();

        return $this->render('event/past_event_details.html.twig', [
            'pastEvent' => $pastEvent,
            'user' => $user,
            'photos' => $photos,
            'photoForm' => $photoForm->createView(),
            'commentForm' => $commentForm,
        ]);
    }

    /**
     * @param int $idPhoto
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/photo/like/{idPhoto}", name="idea_photo_add_like", methods="GET", requirements={"idPhoto" = "\d+"})
     */
    public function ajaxPhotoAddLike(int $idPhoto): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();

        $photo = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findOneBy(['id' => $idPhoto]);

        if (!$photo) {
            return $this->json(['status' => 'error', 'message' => 'Aucune photo associe a l url']);
        }

        $user = $this->getUser();

        if ($photo->getUsersLike()->contains($user) ) {
            return $this->json(['status' => 'error', 'message' => 'Vous avez deja like cette photo']);
        }

        $photo->addUserLike($user);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'Le like a bien ete pris en compte']);
    }

    /**
     * @param int $idPhoto
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/photo/de-like/{idPhoto}", name="idea_photo_remove_like", methods="GET", requirements={"idPhoto" = "\d+"})
     */
    public function ajaxPhotoRemoveLike(int $idPhoto): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();

        $photo = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findOneBy(['id' => $idPhoto]);

        if (!$photo) {
            return $this->json(['status' => 'error', 'message' => 'Aucune photo associe a l url']);
        }

        $user = $this->getUser();

        if (!$photo->getUsersLike()->contains($user) ) {
            return $this->json(['status' => 'error', 'message' => 'Vous n avez pas like cette photo']);
        }

        $photo->removeUserLike($user);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'Le retrait du like bien ete pris en compte']);
    }
}
