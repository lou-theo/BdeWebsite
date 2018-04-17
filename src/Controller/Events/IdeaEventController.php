<?php

namespace App\Controller\Events;

use App\Entity\Event;
use App\Entity\IdeaEvent;
use App\Form\EventForm;
use App\Form\IdeaEventForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IdeaEventController extends Controller
{
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

        $user = $this->getUser();

        return $this->render('event/idea_event.html.twig', [
            'ideaEventList' => $ideaEventList,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param int $idIdeaEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/boite-a-idee/{idIdeaEvent}", name="idea_event_details", requirements={"idIdeaEvent" = "\d+"})
     */
    public function ideaEventDetailsAction(int $idIdeaEvent)
    {
        $ideaEvent = $this->getDoctrine()
            ->getRepository(IdeaEvent::class)
            ->findOneBy(['id' => $idIdeaEvent]);

        if (!$ideaEvent) {
            throw $this->createNotFoundException('Aucune proposition d\'évènement associé à l\'url');
        }

        $user = $this->getUser();

        return $this->render('event/idea_event_details.html.twig', [
            'ideaEvent' => $ideaEvent,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int $idIdeaEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/boite-a-idee/accept/{idIdeaEvent}", name="idea_event_acceptation", requirements={"idIdeaEvent" = "\d+"})
     */
    public function ideaEventAcceptationAction(Request $request, int $idIdeaEvent)
    {
        $this->denyAccessUnlessGranted('ROLE_BDE', null, 'Vous n\'avez pas accès à cette fonction !');

        $ideaEvent = $this->getDoctrine()
            ->getRepository(IdeaEvent::class)
            ->findOneBy(['id' => $idIdeaEvent]);

        if (!$ideaEvent) {
            throw $this->createNotFoundException('Aucune proposition d\'évènement associé à l\'url');
        }

        $event = new Event();

        $event->setTitle($ideaEvent->getTitle());
        $event->setDescription($ideaEvent->getDescription());
        $event->setPicture($ideaEvent->getPicture());

        $form = $this->createForm(EventForm::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->remove($ideaEvent);
            $em->flush();
            return $this->redirectToRoute('event_idea');
        }

        return $this->render('event/idea_event_acceptation.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }
}
