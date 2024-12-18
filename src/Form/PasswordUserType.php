<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
Use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Indiquez le mot de passe actuel'
                ],
                'mapped' => false,
            ])
            /*
                plainPassword n'est lié à aucune entité, cela n'existe pas dans la table,
                du coup on passe le paramètre mapped à false sinon Symfony ne va rien comprendre,
                le mapping se fait via la propriété hash_property_path
            */
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30
                    ])
                ],
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Indiquez le nouveau mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez le nouveau mot de passe'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success' // Aller voir dans la doc de BootStrap sur les boutons
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                $form = $event->getForm();

                // On récupère l'objet User dans le formulaire injecté
                $user = $form->getConfig()->getOptions()['data'];

                // On récupère l'interface PasswordHasher injectée dans l'AccountController
                // et on n'oublie pas de l'ajouter dans les options via la méthode configureOptions()
                // qui se trouve en dessous
                $passwordHasher = $form->getConfig()->getOption('passwordHasher');
                $isValid = $passwordHasher->isPasswordValid(
                                $user,
                                $form->get('actualPassword')->getData()
                           );
                           
                if (! $isValid) {
                    $form->get('actualPassword')->addError(new FormError(
                        "Mot de passe actuel erroné"));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' =>  null
        ]);
    }
}
