<?php

namespace App\Controller\Stripe;

use Stripe\Stripe;
use App\Entity\Cart;
use Stripe\Checkout\Session;

use App\Services\CartServices;
use App\Services\OrderServices;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeCheckoutSessionController extends AbstractController
{
    /**
     * @Route("/create-checkout-session/{reference}", name="create_checkout_session")
     */
    public function index(?Cart $cart, OrderServices $orderServices, CartRepository $repoCart,
                           EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if(!$cart){
          return $this->redirectToRoute('accueil');
        }
        $order = $orderServices->createOrder($cart);

        Stripe::setApiKey('sk_test_51J077OJKhLQ2081pItEOc0mNywBnmiTwo9z9ewiqkKbswOtY3sPS7fr3gEYFLtebDC7yHRDlEvWQ5aqhFK9lCF3400KjjNZCfs');
        //$line_items = $orderServices->getLineItems($cart);
        
        /*$line_items = [];  //mis dans OrderService.php
        foreach (($cart['products']) as $dataProduct) {
            /*[
                'quantity' => 5,
                'product' => objet
            ]
            $product = $dataProduct['product'];
            $line_items[] = [[
                'price_data'=> [
                    'currency'=> 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getNameProduct()
                    ], 
                ],
                'quantity' => $dataProduct['quantity'],
            ]];
        } 
        $transport = $cart['checkout'];
        $dataTranport = $transport['transport'];
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $dataTransport->getPrice(),
              'product_data' => [
                'name' => 'Transport ( '.$dataTransport->getNameTransport().' )',
              ],
            ],
            'quantity' =>  1,
        ];*/
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            "payment_method_types" => ['card'],
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripesuccesspayment/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripecancelpayment/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeCheckoutSessionId($checkout_session->id);
        $manager->flush();
        
        return $this->json(['id' => $checkout_session->id]);
    }


    /**
     * @Route("/create-session-stripe", name="create_session_stripe")//première méthode sans taxe et frais de livraison
     */
    public function indexBis(CartServices $cartServices, OrderServices $orderServices,
                           EntityManagerInterface $manager): Response  //fonctionne essai le 14/02/2022 remplace fonction index
    {
        $user = $this->getUser();
        $cart = $cartServices->getFullCart();
        if(!$cart){
          return $this->redirectToRoute('accueil');
        }
        $order = $orderServices->createOrder($cart);

        Stripe::setApiKey('sk_test_51J077OJKhLQ2081pItEOc0mNywBnmiTwo9z9ewiqkKbswOtY3sPS7fr3gEYFLtebDC7yHRDlEvWQ5aqhFK9lCF3400KjjNZCfs');
        $line_items = [];
        foreach ($cart['products'] as $dataProduct) {
            /*[
                'quantity' =>5,
                'product' => objet
            ]*/
            $product = $dataProduct['product'];
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                      'name' => $product->getNameProduct(),
                    ],
                ],
                'quantity' =>  $dataProduct['quantity'],
            ];


        }
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            "payment_method_types" => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel/{CHECKOUT_SESSION_ID}',
        ]);

        
        $order->setStripeSessionId($checkout_session->id);
        $manager->flush();
        
        return $this->json(['id' => $checkout_session->id]);
    }
}
