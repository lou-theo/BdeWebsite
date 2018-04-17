<?php

namespace App\Controller\Events;

use App\Entity\Event;
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

        return $this->render('event/past_event.html.twig', [
            'pastEventList' => $pastEventList,
        ]);
    }

    /**
     * @param int $idEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/passes/{idEvent}", name="past_event_details", requirements={"idEvent" = "\d+"})
     */
    public function pastEventDetailsAction(int $idEvent)
    {
        $pastEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$pastEvent) {
            throw $this->createNotFoundException('Aucun évènement associé à l\'url');
        }

        return $this->render('event/past_event_details.html.twig', [
            'pastEvent' => $pastEvent,
        ]);
    }
}
