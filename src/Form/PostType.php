<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // FORMULAIRE POUR METTRE EN LIGNE UN POSTE
            
            // Input pour mettre en ligne du texte pour faire un message.
            ->add('content', TextareaType::class, ['label' => false], [
                'attr' => ['class' => 'form-control',
                'rows' => 6, 'cols' => 40,
                'placeholder' => 'Quoi de neuf',
                'required' => true,
                ],
            ])
            
            // Input pour mettre en ligne une image avec le bundle VichUploader.
            ->add('imageFile', VichFileType::class, 
            [
                'label' => false,
                'mapped' => false,
                'required' => false,
            ], 
            [
                'attr' => ['class' => 'input-image'],
                //'multiple' => true, // Cela permet d'uploader plusieurs fichiers
                'required' => false, // Cela permet de rendre le champ facultatif
                'mapped' => false, 
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}