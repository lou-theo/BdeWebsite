<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */

    public function addAction(Request $request, \Swift_Mailer $mailer)
    {
        /*
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

        $formBuilder
            ->add('mail',      EmailType::class)
            ->add('title',     TextType::class)
            ->add('message',   TextareaType::class)
            ->add('save',      SubmitType::class)
        ;

        $form = $formBuilder->getForm();



        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

            }
        }
         */




        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('bdeexiacesireims@gmail.com')
            ->setTo('bdeexiacesireims@gmail.com')
            ->setBody('Ceci est un test')
        ;

        $mailer->send($message);




        return $this->render('Welcome/index.html.twig'/*, array(
            'form' => $form->createView(),
        )*/);
    }
}


