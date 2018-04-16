<?php

namespace App\Controller;

use App\Entity\Goodies;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
            new Goodies(),
            new Goodies(),
            new Goodies()
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

        $goodiesList[3]->setName('tshirt');
        $goodiesList[3]->setDescription('Soyez fier de représenter votre école ');
        $goodiesList[3]->setPictureFileName('tshirt.jpg');
        $goodiesList[3]->setPrice(15.6);

        $goodiesList[4]->setName('ballonnn');
        $goodiesList[4]->setDescription('pour jouer');
        $goodiesList[4]->setPictureFileName('ballon.jpg');
        $goodiesList[4]->setPrice(15.6);

        $goodiesList[5]->setName('mug2');
        $goodiesList[5]->setDescription('Pour boire son café');
        $goodiesList[5]->setPictureFileName('mug.jpg');
        $goodiesList[5]->setPrice(6.6);
        return $this->render('ecommerce/index.html.twig', [
            'goodiesList' => $goodiesList,
        ]);
    }
}
