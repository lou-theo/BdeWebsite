<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EcommerceController extends Controller
{
    /**
     * @Route("/ecommerce", name="ecommerce")
     */
    public function indexAction()
    {
        return $this->render('ecommerce/index.html.twig', [
            'controller_name' => 'EcommerceController',
        ]);
    }
}
