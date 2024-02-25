<?php

namespace App\Form;

use App\Entity\User; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; 
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class RegistrationSupervisorCentralFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [ new NotBlank([ 'message' => 'Le champ nom ne peut pas être vide.', ]), ]
            ])
            ->add('Prenoms', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [ new NotBlank([ 'message' => 'Le champ prénoms ne peut pas être vide.', ]), ]
            ])
            ->add('Telephone', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [ new NotBlank([ 'message' => 'Le champ téléphone ne peut pas être vide.', ]), ]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [ 
                    new NotBlank([ 'message' => 'Le champ email ne peut pas être vide.', ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accépter les termes et conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins 8 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]), 
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
