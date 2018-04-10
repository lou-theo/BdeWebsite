<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */

    public function indexAction()
    {
        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
        ]);




        /*public function contact(Request $request)
    {
    $defaultData = array('message' => 'Type your message here');
    $form = $this->createFormBuilder($defaultData)
    ->add('name', TextType::class)
    ->add('email', EmailType::class)
    ->add('message', TextareaType::class)
    ->add('send', SubmitType::class)
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    // data is an array with "name", "email", and "message" keys
    $data = $form->getData();
    }*/
    }
}


