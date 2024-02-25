<?php

namespace App\Form;

use App\Entity\AnneePrevisionnelle;
use App\Entity\District;
use App\Entity\Groupe;
use App\Entity\Province;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder 
            ->add('Annee', EntityType::class, [
                'class' => AnneePrevisionnelle::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.Annee', 'DESC');
                },
                'choice_label' => 'Annee',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'Choisir Année',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Provinces', EntityType::class, [
                'class' => Province::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.NomFR', 'ASC');
                },
                'choice_label' => 'NomFR',
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'placeholder' => 'Choisir province',
                'attr'=> ['class' => 'mul-select form-control', 'onchange' => 'getDistricts(this.value);']
            ])
            ->add('districts', EntityType::class, [
                'class' => District::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.Nom', 'ASC');
                },
                'choice_label' => 'Nom',
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'placeholder' => 'Choisir district',
                'attr'=> ['class' => 'mul-select-district form-control']
            ])
            ->add('zone', ChoiceType::class, [
                'choices' => [
                    'Enclavé' => 'enclave',
                    'Non Enclavé' => 'non_enclave',
                ],
                'placeholder' => 'Choisir zone',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
