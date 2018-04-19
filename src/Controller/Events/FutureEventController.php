<?php

namespace App\Controller\Events;

use App\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FutureEventController extends Controller
{
    /**
     * @Route("/evenements/a-venir", name="future_event")
     */
    public function FutureEventAction()
    {
        $futureEventList = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAllFutureEvent();

        $user = $this->getUser();

        return $this->render('event/future_event.html.twig', [
            'futureEventList' => $futureEventList,
            'user' => $user,
        ]);
    }

    /**
     * @param int $idEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/a-venir/{idEvent}", name="future_event_details", requirements={"idEvent" = "\d+"})
     */
    public function futureEventDetailsAction(int $idEvent)
    {
        $futureEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$futureEvent) {
            throw $this->createNotFoundException('Aucun évènement associé à l\'url');
        }

        if ($futureEvent->getEventDate() < new \DateTime("now")) {
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $user = $this->getUser();

        return $this->render('event/future_event_details.html.twig', [
            'futureEvent' => $futureEvent,
            'user' => $user,
        ]);
    }

    /**
     * @param int $idEvent
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/a-venir/participer/{idEvent}", name="event_add_participate", methods="GET", requirements={"idEvent" = "\d+"})
     */
    public function ajaxEventAddParticipate(int $idEvent): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$event) {
            return $this->json(['status' => 'error', 'message' => 'Aucun evenement associe à l url']);
        }

        $user = $this->getUser();

        if ($event->getUsersParticipate()->contains($user) ) {
            return $this->json(['status' => 'error', 'message' => 'Vous participez deja a cet evenement']);
        }

        if ($event->getEventDate() < new \DateTime("now")) {
            return $this->json(['status' => 'error', 'message' => 'Vous ne pouvez pas participer a un evenement passe']);
        }

        $event->addUserParticipate($user);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'La participation a bien ete pris en compte']);
    }

    /**
     * @param int $idEvent
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/a-venir/de-participer/{idEvent}", name="event_remove_participate", methods="GET", requirements={"idEvent" = "\d+"})
     */
    public function ajaxEventRemoveParticipate(int $idEvent): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$event) {
            return $this->json(['status' => 'error', 'message' => 'Aucun evenement associe à l url']);
        }

        $user = $this->getUser();

        if (!$event->getUsersParticipate()->contains($user) ) {
            return $this->json(['status' => 'error', 'message' => 'Vous ne participez a cet evenement']);
        }

        if ($event->getEventDate() < new \DateTime("now")) {
            return $this->json(['status' => 'error', 'message' => 'Vous ne pouvez pas vous desinscrire d un evenement passe']);
        }

        $event->removeUserParticipate($user);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'Le retrait de participation a bien ete pris en compte']);
    }

    /**
     * @param int $idEvent
     * @return Response
     *
     * @Route("/evenements/csv/{idEvent}", name="download_csv", requirements={"idEvent" = "\d+"})
     */
    public function downloadCsvAction(int $idEvent)
    {
        $this->denyAccessUnlessGranted('ROLE_BDE', null, 'Vous n\'avez pas accès à cette fonction !');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$event) {
            throw $this->createNotFoundException('Aucun évènement associé à l\'url');
        }

        $list = $event->getUsersParticipate();

        $filename = 'liste_participants.csv';

        $fileContent="id, prenom, NOM, mail\n";
        foreach($list as $line)
        {

            $fileContent.= $line->getId() . ", " . $line->getFirstName() . ", " . $line->getLastName() . ", " . $line->getMail();
            $fileContent.= "\n";
        }


        $response = new Response($fileContent);
        $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        $filename
    );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
