<?php

namespace App\Controller\Events;

use App\Entity\IdeaEvent;
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

        return $this->render('event/idea_event.html.twig', [
            'ideaEventList' => $ideaEventList,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $idIdeaEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/boite-a-idee/{idIdeaEvent}", name="idea_event_details", requirements={"idIdeaEvent" = "\d+"})
     */
    public function pastEventDetailsAction(int $idIdeaEvent)
    {
        $pastEvent = $this->getDoctrine()
            ->getRepository(IdeaEvent::class)
            ->findOneBy(['id' => $idIdeaEvent]);

        if (!$pastEvent) {
            throw $this->createNotFoundException('Aucune proposition d\'évènement associé à l\'url');
        }

        return $this->render('event/idea_event_details.html.twig', [
            'pastEvent' => $pastEvent,
        ]);
    }
}
