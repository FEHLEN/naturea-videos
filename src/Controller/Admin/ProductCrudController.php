<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nameProduct'),
            TextareaField::new('description'),
            TextareaField::new('moreInformations')->hideOnIndex(),
            MoneyField::new('price')->setCurrency('EUR'),
            IntegerField::new('quantityProduct'),
            BooleanField::new('isBest', 'Le meilleur'),
            BooleanField::new('isNew', 'Nouveauté'),
            BooleanField::new('isFeatured', 'Moderne'),
            BooleanField::new('isSpecialOffer', 'Offre spéciale'),
            AssociationField::new('category'),
            TextField::new('tags')->hideOnIndex(),
            SlugField::new('slug')->setTargetFieldName('nameProduct'),
            DateTimeField::new('createdAt'),
            ImageField::new('image')->setBasePath('assets/uploads/products/')
                                    ->setUploadDir('public/assets/uploads/products/')
                                    ->setUploadedFileNamePattern('[randomhash].[extension]')
        ];
    }
    
}
