<?php

namespace App\Controller;

use App\Entity\Goodies;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EcommerceController extends Controller
{
    /**
     * @Route("/boutique", name="ecommerce")
     */
    public function indexAction()
    {
        $goodiesList = [
            new Goodies(),
            new Goodies(),
            new Goodies(),
        ];

        $goodiesList[0]->setName('casquette');
        $goodiesList[0]->setDescription('Une casquette pour ne pas avoir trop chaud');
        $goodiesList[0]->setPictureFileName('casquette.jpg');
        $goodiesList[0]->setPrice(10.6);

        $goodiesList[1]->setName('mug');
        $goodiesList[1]->setDescription('Pour boire son café');
        $goodiesList[1]->setPictureFileName('mug.jpg');
        $goodiesList[1]->setPrice(6.6);

        $goodiesList[2]->setName('ballon');
        $goodiesList[2]->setDescription('pour jouer');
        $goodiesList[2]->setPictureFileName('ballon.jpg');
        $goodiesList[2]->setPrice(15.6);

        return $this->render('ecommerce/index.html.twig', [
            'goodiesList' => $goodiesList,
        ]);
    }
}
