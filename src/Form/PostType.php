<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control', 
                'rows' => 6, 'cols' => 40, 
                'placeholder' => 'Quoi de neuf,'],
            ])
            
            ->add('image', FileType::class, [
                'attr' => ['class' => 'input-image'],
                //'multiple' => true, // Permet d'uploader plusieurs fichiers
                //'mapped' => false, // Assurez-vous de définir ceci si le champ n'est pas lié à une entité
                'required' => false, // Optionnel : permet de rendre le champ facultatif
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}