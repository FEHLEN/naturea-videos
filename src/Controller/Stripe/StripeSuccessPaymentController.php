<?php

namespace App\Controller\Stripe;

use App\Entity\Orders;
use App\Services\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    /**
     * @Route("/stripesuccesspayment/{stripeSessionId}", name="stripe_success_payment")
     */
    public function index(?Orders $orders, CartServices $cartServices, EntityManagerInterface $manager): Response
    {
        //dd($order);
        if(!$orders || $orders->getUser() !== $this->getUser()){
            return $this->redirectToRoute('accueil');
        }
        if(!$orders->getIsPaid()){
            //commande est payée
            $orders->setIsPaid(true);
            $manager->flush();

            $cartServices->deleteCart();
            //envoi d'un email au client
        }
        
        return $this->render('stripe/stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
            'orders' => $orders,
        ]);
    }
}
