<?php

namespace App\Controller;

use App\Entity\IdeaEvent;
use App\Form\IdeaEventForm;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/boite-a-idee", name="event_idea")
     */
    public function ideaEventAction(Request $request)
    {
        $ideaEventList = $this->getDoctrine()
            ->getRepository(IdeaEvent::class)
            ->findAll();

        $ideaEvent = new IdeaEvent();
        $form = $this->createForm(IdeaEventForm::class, $ideaEvent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
            $ideaEvent->setUserOwner($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($ideaEvent);
            $em->flush();
            return $this->redirectToRoute('event_idea');
        }

        return $this->render('event/idea_event.html.twig', [
            'ideaEventList' => $ideaEventList,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/evenements/passes", name="past_event")
     */
    public function pastEventAction()
    {
        return $this->render('event/past_event.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
}
