<?php

namespace App\Controller\Events;

use App\Entity\Event;
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
    public function pastEventDetailsAction(int $idEvent)
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
     * @return Response
     *
     * @Route("/evenements/csv/{idEvent}", name="download_csv", requirements={"idEvent" = "\d+"})
     */
    public function downloadFileAction(int $idEvent)
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

        $fileContent="";
        foreach($list as $line)
        {
            foreach($line as $unite)
            {
                $fileContent.= $unite . ",";
            }
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
