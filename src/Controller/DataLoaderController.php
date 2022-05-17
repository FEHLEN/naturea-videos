<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Categories;
use App\Entity\AccueilSlider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataLoaderController extends AbstractController
{
    /**
     * @Route("/data", name="data_loader")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        //lecture des fichiers en json voir ou on est situé
        //dd(__DIR__);//nous sommes dans le dossier controller=> il faut aller à la racine donc sortir deux fois
        //dd(dirname(dirname(__DIR__)));
        $file_products = dirname(dirname(__DIR__))."\Product-naturea.json";
        $file_categories = dirname(dirname(__DIR__))."\categories-naturea.json";
        $file_sliders = dirname(dirname(__DIR__))."\accueil_slider-naturea.json";
        //décoder le json en string pour le format tableau php avec une clé 0, l'objet rows
        $data_products = json_decode(file_get_contents($file_products))[0]->rows;
        $data_categories = json_decode(file_get_contents($file_categories))[0]->rows;
        $data_sliders = json_decode(file_get_contents($file_sliders))[0]->rows;
        //dd($data_products);
        $products = [];
        foreach ($data_products as $data_product) {
            $product = new Product();
            $product->setNameProduct($data_product[1])
                    ->setDescription($data_product[2])
                    ->setMoreInformations($data_product[3])
                    ->setPrice($data_product[4])
                    ->setIsBest($data_product[5])
                    ->setIsNew($data_product[6])
                    ->setIsFeatured($data_product[7])
                    ->setIsSpecialOffer($data_product[8])
                    ->setImage($data_product[9])
                    ->setCreatedAt($data_product[10])
                    ->setSlug($data_product[11])
                    ->setTags($data_product[12])
                    ->setQuantityProduct($data_product[13]);
            //$manager->persist($product);
            //$products[] = $product;
        }
        $sliders = [];
        foreach ($data_sliders as $data_slider) {
            $slider = new AccueilSlider();
            $slider->setTitle($data_slider[1])
                   ->setDescription($data_slider[2])
                   ->setButtonMessage($data_slider[3])
                   ->setButtonUrl($data_slider[4])
                   ->setImageSlider($data_slider[5])
                   ->setIsDisplayed($data_slider[6]);
            //$manager->persist($slider);
            //$sliders[] = $slider;
        }

        $categories = [];
        foreach ($data_categories as $data_category) {
            $category = new Categories();
            $category->setNameCategorie($data_category[1])
                     ->setDescription($data_category[2])
                     ->setImage($data_category[3]);
            //$manager->persist($category);
            //$categories[] = $category;
        }

        //$manager->flush();  //commenter si pas utilisé
        return $this->json([
            'message' => 'Votre enregistrement est terminé, avec succès !',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
}
