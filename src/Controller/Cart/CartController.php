<?php

namespace App\Controller\Cart;

use App\Services\CartServices;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $cartServices;
    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }
    /**
     * @Route("/cart", name="panier")
     */
    public function index(): Response
    {
        //$session->set("cart", ["name"=>"session"]); // pour simplifier création d'un service
        //$cart = $session->get("cart");
        $cart = $this->cartServices->getFullCart();
        if (!isset($cart['products'])){
            return $this->redirectToRoute("accueil");
        }
        //$cartServices->addToCart(4);
        //dd($cartServices->getCart());//test ok pour affichage
        
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart'=> $cart,
        ]);
    }
    /**
     * @Route("/cart/add/{id}", name = "cartAdd")
     */
    public function addToCart($id): Response{
        //$cartServices->deleteCart();
        $this->cartServices->addToCart($id);
        //dd($cartServices->getFullCart());//test ok pour l'ajout
        return $this->redirectToRoute("panier");
        //return $this->render('cart/index.html.twig', [
           // 'controller_name' => 'CartController',]);
    }

    /**
     * @Route("/cart/delete/{id}", name = "cartDelete")
     */
    public function deleteFromCart($id): Response{
       
        $this->cartServices->deleteFromCart($id);
        //dd($cartServices->getFullCart());//test ok pour le delete
        return $this->redirectToRoute("panier");
        //return $this->render('cart/index.html.twig', [
        //    'controller_name' => 'CartController',]);
    }

    /**
     * @Route("/cart/deleteAll/{id}", name = "cartDeleteAll")
     */
    public function deleteAllToCart($id): Response{
       
        $this->cartServices->deleteAllToCart($id);
        //dd($cartServices->getFullCart());//test ok pour le delete
        return $this->redirectToRoute("panier");
        //return $this->render('cart/index.html.twig', [
        //    'controller_name' => 'CartController',]);
    }
}
