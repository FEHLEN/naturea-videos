<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Transporteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; //injection des données de l'utilisateur

        $builder //construction du formulaire
            ->add('address', EntityType::class, [
                'class'=> Address::class,
                'required'=>true,
                'choices'=>$user->getAddresses(),
                'multiple'=>false,
                'expanded'=>true //design un checkbox
             ])//adress est le nom de la table
            ->add('transporteurs', EntityType::class, [
                'class'=> Transporteurs::class,
                'required'=>true,
                'multiple'=>false,
                'expanded'=>true
            ])//options du champs, requis, choix multiple = non un seul choix, étendu = oui
            ->add('informations', TextareaType::class, [
                'required'=>false
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
