<?php

namespace App\Controller\Admin;

use App\Entity\AccueilSlider;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AccueilSliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccueilSlider::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'titre'),
            TextareaField::new('description'),
            TextField::new('buttonMessage', 'Message bouton'),
            TextareaField::new('buttonUrl', 'Adresse Url'),
            ImageField::new('imageSlider')->setBasePath('assets/uploads/slider/')
                                    ->setUploadDir('public/assets/uploads/slider/')
                                    ->setUploadedFileNamePattern('[randomhash].[extension]'),
            BooleanField::new('isDisplayed', 'Diffusé')
        ];
    }
    
}
