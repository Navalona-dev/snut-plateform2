<?php

namespace App\Form;

use App\Entity\District;
use App\Entity\Province;
use App\Entity\Region;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isEligibleForCreni')
            ->add('isEligibleForCrenas')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Enclavé' => 'enclave',
                    'Non Enclavé' => 'non_enclave',
                ],
                'placeholder' => 'Choisir zone',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])

            ->add('province', EntityType::class, [
                'class' => Province::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.NomFR', 'ASC');
                },
                'choice_label' => 'NomFR',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir province',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.Nom', 'ASC');
                },
                'choice_label' => 'Nom',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir région',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Densite', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Superficie', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Population', IntegerType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => District::class,
        ]);
    }
}
