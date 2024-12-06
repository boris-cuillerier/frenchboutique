<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => [ // attr pour 'attributes'
                    'placeholder' => 'prenom.nom@domaine.fr'
                ]
            ])
            /*
                plainPassword n'est lié à aucune entité, cela n'existe pas dans la table,
                du coup on passe le paramètre mapped à false sinon Symfony ne va rien comprendre,
                le mapping se fait via la propriété hash_property_path
            */
            ->add('plainPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options'  => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Indiquez le mot de mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez le mot de mot de passe'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Indiquez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Indiquez votre nom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success' // Aller voir dans la doc de BootStrap sur les boutons
                ]
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
