<?php

namespace App\Controller\Events;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Photo;
use App\Form\CommentForm;
use App\Form\PhotoForm;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

        $user = $this->getUser();

        return $this->render('event/past_event.html.twig', [
            'pastEventList' => $pastEventList,
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int $idEvent
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/evenements/passes/{idEvent}", name="past_event_details", requirements={"idEvent" = "\d+"})
     */
    public function pastEventDetailsAction(Request $request, int $idEvent)
    {
        $pastEvent = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBy(['id' => $idEvent]);

        if (!$pastEvent) {
            throw $this->createNotFoundException('Aucun évènement associé à l\'url');
        }

        if ($pastEvent->getEventDate() >= new \DateTime("now")) {
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['event' => $pastEvent]);

        $photo = new Photo();
        $photoForm = $this->createForm(PhotoForm::class, $photo);
        $photoForm->handleRequest($request);

        if ($photoForm->isSubmitted() && $photoForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
            $photo->setUserOwner($this->getUser());
            $photo->setEvent($pastEvent);

            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $commentForm = $this->createForm(CommentForm::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

            $commentData = $commentForm->getData();
            $comment = new Comment();

            $photoCommented = $this->getDoctrine()
                ->getRepository(Photo::class)
                ->findOneBy(['id' => $commentData['idPhoto']]);

            $comment->setMessage($commentData['message']);
            $comment->setPhoto($photoCommented);
            $comment->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('past_event_details', ['idEvent' => $idEvent]);
        }

        $user = $this->getUser();

        return $this->render('event/past_event_details.html.twig', [
            'pastEvent' => $pastEvent,
            'user' => $user,
            'photos' => $photos,
            'photoForm' => $photoForm->createView(),
            'commentForm' => $commentForm,
        ]);
    }

    /**
     * @Route("/evenements/passes/{idEvent}/download", name="download_picture_event")
     * @param Request $request
     * @param int $idEvent
     */
    public function downloadPicture(Request $request, int $idEvent)
    {
        /*$this->denyAccessUnlessGranted('ROLE_CESI', null, 'Vous n\'avez pas accès à cette fonction !');
        */
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findOneBy(['event' => $idEvent]);

        if (!$photos) {
            throw $this->createNotFoundException('Aucun évènement ou photos associés à l\'url');
        }

        $response = new Response();
        $filename = $photos->getFileName();
        $filepath = "C:\wamp64\www\BdeWebsite\public\Image\PhotoEvent\\".$filename;
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/jpg');
        $response->setContent(file_get_contents($filepath));

        $zipname = 'photoEvent.zip';
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);

        $zip = new \ZipArchive();
        if ($zip->open('photoEvent.zip') === TRUE) {
            foreach ($photos as $photo) {
                $zip->addFile($filepath);
            }
            $zip->close();
            echo 'ok';
        } else {
            echo 'failed';
        }

        return $response;
    }
}