<?php

namespace App\Controller\Admin;

use App\Entity\Transporteurs;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransporteursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transporteurs::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nameTransport'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR')
        ];
    }
    
}
