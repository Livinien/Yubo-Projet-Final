<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, ['label' => '*Prénom'])
            ->add('lastname', null, ['label' => '*Nom'])
            ->add('email', null, ['label' => '*Email'])
            ->add('picture', null, ['label' => 'Image'])
            ->add('password', RepeatedType::class, 
                    [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les deux champs du mot de passe doivent être identiques',
                    'label' => 'Mot de Passe',
                    'required' => true,
                    'constraints' => [
                        new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        "Vous devez impérativement avoir 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux pour créer votre compte.")
                    ],
                    
                    'first_options' => [
                        'label' => '*Mot de Passe',
                        'help' => "*Vous avez besoin d'un mot de passe de 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux.",
                        
                    ],
                    
                    'second_options' => [
                        'label'=> '*Confirmez votre mot de passe',
                    ],
                    
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}