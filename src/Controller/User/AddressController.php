<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Form\AddressType;
use App\Services\CartServices;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    private $session;
    public function __construct(SessionInterface $session){
        $this->session = $session;
    }
    /**
     * @Route("/", name="address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="address_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CartServices $cartServices): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $address->setUser($user);
            $entityManager->persist($address);
            $entityManager->flush();
            //vérifier si il y a un panier plein
            if($cartServices->getFullCart()){
                $this->addFlash('address_message', 'Votre adresse a bien été enregistrée');
                return $this->redirectToRoute('checkout');
            };

            $this->addFlash('address_message', 'Votre adresse a bien été enregistrée');
            return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET", "POST"})
     */
    
    public function edit(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if($this->session->get('checkout_data')){//si on vient du formulaire de changement d'adresse
               $data = $this->session->get('checkout_data');
               $data['address'] = $address;
               $this->session->set('checkout_data',$data);
                return $this->redirectToRoute('checkoutConfirm');
            }
            $this->addFlash('address_message', 'Votre adresse a bien été modifiée');
            return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods={"POST"})
     */
    public function delete(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();
            $this->addFlash('address_message', 'Votre adresse a bien été supprimée');
        }

        return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
    }
}
