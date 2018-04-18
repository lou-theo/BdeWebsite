<?php

namespace App\Controller;

use App\Controller\Events\FutureEventController;
use App\Entity\Event;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends Controller
{
    /**
     * @Route("/api/evenements")
     */
    public function listeAction()
    {
        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        $dataEvent = array('events' => array());
        foreach ($events as $oneEvent) {
            $dataEvent['events'][] = $this->serializeEvents($oneEvent);
        }
        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($dataEvent), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Route("/api/evenements/{idEvent}")
     * @param int $idEvent
     * @return Response
     */
    public function oneAction(int $idEvent)
    {
        $oneEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!isset($oneEvent) || empty($oneEvent))
        {
            $error='Cet evenement n\'existe pas.';
            $response = new \Symfony\Component\HttpFoundation\Response(json_encode($error), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        else
        {
            $dataEvent=$this->serializeEvents($oneEvent);
            $response = new \Symfony\Component\HttpFoundation\Response(json_encode($dataEvent), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }


    private function serializeEvents(\App\Entity\Event $events)
    {
        return array(
            'idEvent' =>  $events->getId(),
            'titleEvent' =>  $events->getTitle(),
            'pictureEvent' =>  $events->getPicture(),
            'eventDateEvent' =>  $events->getEventDate(),
            'priceEvent' => $events->getPrice()
        );
    }


    public function newAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $dataEvent = $this->serializeEvents($oneEvent);
        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($dataEvent), 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
