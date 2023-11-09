<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use App\Form\DataTransformer\FileToStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditType extends AbstractType
{

    public function __construct(FileToStringTransformer $fileToStringTransformer)
    {
        $this->fileToStringTransformer = $fileToStringTransformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, ['label' => false], [
                'attr' => ['class' => 'form-control',
                'rows' => 6, 'cols' => 40,
                'placeholder' => 'Quoi de neuf',
                'required' => true,
                ],
            ])
            
            ->add('image', FileType::class, ['label' => false], [
                'attr' => ['class' => 'input-image'],
                //'multiple' => true, // Permet d'uploader plusieurs fichiers
                'required' => false, // Optionnel : permet de rendre le champ facultatif
                'mapped' => false, // Assurez-vous de définir ceci si le champ n'est pas lié à une entité
                ])
            ;
            
            // $builder->get('image')->addModelTransformer($this->fileToStringTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here

            'data_class' => Post::class
        ]);
    }
}