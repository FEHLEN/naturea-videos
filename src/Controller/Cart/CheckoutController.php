<?php

namespace App\Controller\Cart;

use App\Form\CheckoutType;
use App\Services\CartServices;
use App\Services\OrderServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices, SessionInterface $session)
    {
        $this->cartServices = $cartServices;
        $this->session = $session;
    }
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();    
        $cart = $this->cartServices->getFullCart();

        if(!isset($cart['products'])){
            return $this->redirectToRoute("accueil");
        }
        if(!$user->getAddresses()->getValues()){
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("address_new");
        }
        /*if($this->session->get('checkout_data')){
            return $this->redirectToRoute('checkoutConfirm');
        }*/
        $form = $this->createForm(CheckoutType::class, null, ['user'=>$user]);
        $form->handleRequest($request);
        //traitement du formulaire voir methode checkoutConfirm

        return $this->render('checkout/index.html.twig', [
            'cart'=>$cart,
            'checkout'=>$form->createView()
        ]);
    }
    /**
     * @Route("/checkout/confirm", name="checkoutConfirm")
     */
    public function confirm(Request $request, OrderServices $orderServices): Response
    {
        $user = $this->getUser();    
        $cart = $this->cartServices->getFullCart();

        if(!isset($cart['products'])){
            return $this->redirectToRoute("accueil");
        }
        if(!$user->getAddresses()->getValues()){
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("address_new");
        }
        
        $form = $this->createForm(CheckoutType::class, null, ['user'=>$user]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() || $this->session->get('checkout_data')) {
            if($this->session->get('checkout_data')){
                $data = $this->session->get('checkout_data');
            } else {
                $data = $form->getData();
                //dd($data);
                $this->session->set('checkout_data',$data);
            }
            
              
            $address = $data['address'];
            $transport = $data['transporteurs'];
            $informations = $data['informations'];
            
             //save panier
             $cart['checkout'] = $data;
             //dd($cart);

             $reference = $orderServices->saveCart($cart,$user);
            return $this->render('checkout/confirm.html.twig', [
                'cart'=>$cart,
                'address' =>$address,
                'transporteurs' =>$transport,
                'informations' =>$informations,
                'reference' =>$reference,
                'checkout'=>$form->createView()
            ]);
        }
        return $this->redirectToRoute('checkout');
    }
    /**
     * @Route("/checkout/show", name="checkoutShow")
     */
    public function checkoutShow(): Response
    {
        $this->session->set('checkout_data',[]);
        return $this->redirectToRoute("checkout");
    }

}
