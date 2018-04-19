<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartGoodies;
use App\Entity\Category;
use App\Entity\Goodies;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param string $category
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/boutique/categorie/{category}", name="category_search")
     */
    public function categorySearchAction(string $category)
    {
        $categorySearch = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $category]);

        if (!$categorySearch) {
            throw $this->createNotFoundException('Aucune catégorie associé à l\'url');
        }

        $goodiesList = $this->getDoctrine()
            ->getRepository(Goodies::class)
            ->findAllGoodiesByCategory($categorySearch);

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

        $currentCart->setPurchaseDate(new \DateTime());
        $currentCart->setState(Cart::WAITING);
        $em->flush();

        $mailer->send($message);

        return $this->render('ecommerce/purchase_confirmation.html.twig', [
            'currentCart' => $currentCart,
            'cartGoodiesList' => $cartGoodiesList,
        ]);
    }

    /**
     * @param int $idGoodies
     * @param int $quantity
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/boutique/add/{idGoodies}/{quantity}", name="cart_add_goodies", methods="GET", requirements={"idGoodies" = "\d+", "quantity" = "\d+"})
     */
    public function ajaxCartAddGoodies(int $idGoodies, int $quantity): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $user]);

        $goodies = $this->getDoctrine()
            ->getRepository(Goodies::class)
            ->findOneBy(['id' => $idGoodies]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $em->persist($cart);
        }

        if (!$goodies) {
            return $this->json(['status' => 'error', 'message' => 'Aucun goodies associe à l url']);
        }

        if ($quantity < 1) {
            return $this->json(['status' => 'error', 'message' => 'Quantite invalide']);
        }

        $cartGoodies = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findOneBy(['cart' => $cart, 'goodies' => $goodies]);

        if (!$cartGoodies) {
            $cartGoodies = new CartGoodies();
            $cartGoodies->setCart($cart);
            $cartGoodies->setGoodies($goodies);
            $cartGoodies->setQuantity($quantity);
            $em->persist($cartGoodies);
        } else {
            $cartGoodies->setQuantity($cartGoodies->getQuantity() + $quantity);
        }

        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'L ajout a bien ete pris en compte']);
    }

    /**
     * @param int $idGoodies
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/boutique/remove/{idGoodies}", name="cart_remove_goodies", methods="GET", requirements={"idGoodies" = "\d+"})
     */
    public function ajaxCartRemoveGoodies(int $idGoodies): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $user]);

        $goodies = $this->getDoctrine()
            ->getRepository(Goodies::class)
            ->findOneBy(['id' => $idGoodies]);

        if (!$cart) {
            return $this->json(['status' => 'error', 'message' => 'Vous ne pouvez rien supprimer : pas de panier']);
        }

        if (!$goodies) {
            return $this->json(['status' => 'error', 'message' => 'Aucun goodies associe à l url']);
        }

        $cartGoodies = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findOneBy(['cart' => $cart, 'goodies' => $goodies]);

        if (!$cartGoodies) {
            return $this->json(['status' => 'error', 'message' => 'Ce goodies n etait pas dans votre panier']);
        }

        $em->remove($cartGoodies);
        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'Le retrait a bien ete pris en compte']);
    }

    /**
     * @param int $idGoodies
     * @param int $quantity
     * @return JsonResponse
     * @throws \LogicException
     *
     * @Route("/ajax/boutique/change/{idGoodies}/{quantity}", name="cart_change_goodies", methods="GET", requirements={"idGoodies" = "\d+", "quantity" = "\d+"})
     */
    public function ajaxCartChangeGoodies(int $idGoodies, int $quantity): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $user]);

        $goodies = $this->getDoctrine()
            ->getRepository(Goodies::class)
            ->findOneBy(['id' => $idGoodies]);

        if (!$cart) {
            return $this->json(['status' => 'error', 'message' => 'Vous ne pouvez rien modifier : pas de panier']);
        }

        if (!$goodies) {
            return $this->json(['status' => 'error', 'message' => 'Aucun goodies associe à l url']);
        }

        if ($quantity < 1) {
            return $this->json(['status' => 'error', 'message' => 'Quantite invalide']);
        }

        $cartGoodies = $this->getDoctrine()
            ->getRepository(CartGoodies::class)
            ->findOneBy(['cart' => $cart, 'goodies' => $goodies]);

        if (!$cartGoodies) {
            return $this->json(['status' => 'error', 'message' => 'Ce produit n est pas dans le panier']);
        } else {
            $cartGoodies->setQuantity($quantity);
        }

        $em->flush();

        return $this->json(['status' => 'success', 'message' => 'La modification a bien ete pris en compte']);
    }
}
