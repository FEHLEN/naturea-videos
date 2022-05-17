<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\AccueilSliderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(AccueilSliderRepository $repoSlider, ProductRepository $repoProduct): Response
    {
        $products = $repoProduct->findAll();
        $accueilSlider = $repoSlider->findBy(['isDisplayed'=>true]);
        //dd($products);
        $productBest = $repoProduct->findByIsBest(1);
        $productNew = $repoProduct->findByIsNew(1);
        $productFeatured = $repoProduct->findByIsFeatured(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);
       //dd([$productBest, $productNew, $productFeatured, $productSpecialOffer]);
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'products' => $products,
            'productBest' => $productBest,
            'productNew' => $productNew,
            'productFeatured' => $productFeatured,
            'productSpecialOffer' => $productSpecialOffer,
            'accueilSlider'=>$accueilSlider
        ]);
    }
    /**
     * @Route("/product/{slug}", name="product_details")
     */
    public function show(?Product $product):Response
    {
        if(!$product){
            return $this->redirectToRoute("accueil");
        }

        return $this->render("accueil/single_product.html.twig",[
            'product' => $product
        ]);
    }
}
