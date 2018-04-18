<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartGoodies;
use App\Entity\Category;
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
        $goodiesList = $this->getDoctrine()
            ->getRepository(Goodies::class)
            ->findAll();

        $cartGoodies = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findMostPopularGoodies();

        $topGoodiesList = [];
        foreach ($cartGoodies as $item) {
            $topGoodiesList[] = $item->getGoodies();
        }

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('ecommerce/index.html.twig', [
            'goodiesList' => $goodiesList,
            'topGoodiesList' => $topGoodiesList,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/boutique/paniers", name="all_my_carts")
     */
    public function allMyCartsAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();

        $allCarts = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findBy(['user' => $user]);

        $currentCart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $user, 'state' => Cart::PURCHASING]);

        return $this->render('ecommerce/all_my_carts.html.twig', [
            'allCarts' => $allCarts,
            'currentCart' => $currentCart,
        ]);
    }

    /**
     * @param int $idCart
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/boutique/paniers/{idCart}", name="my_carts_details", requirements={"idCart" = "\d+"})
     */
    public function myCartsDetailsAction(int $idCart)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['id' => $idCart]);

        if (!$cart) {
            throw $this->createNotFoundException('Aucun panier associé à l\'url');
        }

        if ($user->getId() != $cart->getUser()->getId()) {
            throw $this->createNotFoundException('Aucun panier associé à l\'url');
        }

        $cartGoodies = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findBy(['cart' => $cart]);

        return $this->render('ecommerce/my_carts_details.html.twig', [
            'cart' => $cart,
            'cartGoodies' => $cartGoodies,
        ]);
    }

    /**
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/boutique/confirmation-commande", name="purchase_confirmation", requirements={"idCart" = "\d+"})
     */
    public function purchaseConfirmationAction(\Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $currentCart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $user, 'state' => Cart::PURCHASING]);

        if (!$currentCart) {
            throw $this->createNotFoundException('Aucun panier n\'a été créé, passez des achats !');
        }

        $cartGoodiesList = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findBy(['cart' => $currentCart]);

        if (!$currentCart) {
            throw $this->createNotFoundException('Votre panier est vide !');
        }

        $message = (new \Swift_Message('Commande sur le site du BDE'))
            ->setFrom('bdeexiacesireims@gmail.com')
            ->setTo('bdeexiacesireims@gmail.com')
            ->setBody(
                $this->renderView(
                    'emails/purchase_confirmation.html.twig', [
                    'currentCart' => $currentCart,
                    'cartGoodiesList' => $cartGoodiesList,
                    'user' => $user,
                ]),
                'text/html'
            );

        $currentCart->setState(Cart::WAITING);
        $em->flush();

        $mailer->send($message);

        return $this->render('ecommerce/purchase_confirmation.html.twig', [
            'currentCart' => $currentCart,
            'cartGoodiesList' => $cartGoodiesList,
        ]);
    }
}
