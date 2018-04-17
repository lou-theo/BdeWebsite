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

    /**
     * @Route("/evenements/{eventnum}/csv", name="download_csv")
     **/
    public function downloadFileAction(int $eventnum)
    {
        $list = array(array('aaa', 'bbb', 'ccc', 'dddd'),
            array(155, 515, 516, 546));

        $filename = 'liste_participants.csv';

        $fileContent="";
        $unite="";
        foreach($list as $line)
        {
            foreach($line as $unite)
            {
                $fileContent.= $unite . ",";
            }
            $fileContent.= "<br>";
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
