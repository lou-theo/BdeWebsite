<?php

namespace App\Controller\Events;

use App\Entity\Event;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        return $this->render('event/future_event.html.twig', [
            'futureEventList' => $futureEventList,
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

        return $this->render('event/future_event_details.html.twig', [
            'futureEvent' => $futureEvent,
        ]);
    }
}
