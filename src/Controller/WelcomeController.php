<?php

namespace App\Controller;

use App\Form\ContactForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function indexAction()
    {
        return $this->render('Welcome/index.html.twig');
    }


    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request, \Swift_Mailer $mailer)
    {

        $formContact = $this->createForm(ContactForm::class);

        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid())
        {
            $contact = $formContact->getData();

            $message = (new \Swift_Message($contact['title']))
                ->setFrom($contact['mail'])
                ->setTo('bdeexiacesireims@gmail.com')
                ->setBody($contact['message'])
            ;

            $mailer->send($message);

            return $this->redirectToRoute('welcome');
        }

        return $this->render('Welcome/contactForm.html.twig', array(
            'form' => $formContact->createView(),
        ));

    }


}


