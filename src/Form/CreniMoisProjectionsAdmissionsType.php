<?php

namespace App\Form;

use App\Entity\CommandeSemestrielle;
use App\Entity\CreniMoisProjectionsAdmissions;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreniMoisProjectionsAdmissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $mois = [
            "Janvier" => "Janvier",
            "Fevrier" => "Fevrier",
            "Mars" => "Mars",
            "Avril" => "Avril",
            "Mai" => "Mai",
            "Juin" => "Juin",
            "Juillet" => "Juillet",
            "Aout" => "Aout",
            "Septembre" => "Septembre",
            "Octobre" => "Octobre",
            "Novembre" => "Novembre",
            "Décembre" => "Décembre"
        ];

        $builder
            ->add('CommandeSemestrielle', EntityType::class, [
                'class' => CommandeSemestrielle::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.Nom', 'ASC');
                },
                'choice_label' => 'Nom',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir groupe',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('Mois01AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois02AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois03AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois04AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois05AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois06AdmissionCreniPrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois01AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois02AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois03AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois04AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois05AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois06AdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois01ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois02ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois03ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois04ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois05ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Mois06ProjectionAnneePrevisionnelle', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
           
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreniMoisProjectionsAdmissions::class,
        ]);
    }
}
