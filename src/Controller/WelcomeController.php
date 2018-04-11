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
    }
    /**
     * @Route("/cgu", name="cgu-mention")
     */
    public function cguAction()
    {
        return $this->render('welcome/cgu.html.twig', [
            'controller_name' => 'WelcomeController',
        ]);
    }
}


