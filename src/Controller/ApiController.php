<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;





class ApiController extends Controller
{
    /**
     * @Route("/api")
     */
    public function listeAction()
    {
        $futureEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();
            //->findOneBy(['id' => $idFutureEvent]);

        $dataEvent = array('futureEvent' => array());
        foreach ($futureEvent as $oneEvent) {
            $dataEvent['futureEvent'][] = $this->serializeFutureEvent($oneEvent);
        }
        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($dataEvent), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function serializeFutureEvent(\App\Entity\Event $futureEvent)
    {
        return array(
            'idEvent' =>  $futureEvent->getId(),
            'titleEvent' =>  $futureEvent->getTitle(),
            'pictureEvent' =>  $futureEvent->getPicture(),
            'eventDateEvent' =>  $futureEvent->getEventDate(),
            'priceEvent' => $futureEvent->getPrice()
        );
    }

    public function newAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $dataEvent = $this->serializeFutureEvent($oneEvent);
        $response = new \Symfony\Component\HttpFoundation\Response(json_encode($dataEvent), 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}

