<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    /**
     * @Route("/evenements", name="future_event")
     */
    public function indexAction()
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/evenements/boite-a-idee", name="event_idea")
     */
    public function ideaEventAction()
    {


        return $this->render('event/idea_event.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * @Route("/evenements/passes", name="past_event")
     */
    public function pastEventAction()
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
}
