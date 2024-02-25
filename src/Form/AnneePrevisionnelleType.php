<?php

namespace App\Form;

use App\Entity\AnneePrevisionnelle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnneePrevisionnelleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Annee', IntegerType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueATPE01', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueAMOX01', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueFichePatient01', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueRegistre01', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueCarnetRapport01', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueATPE02', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueAMOX02', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueFichePatient02', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueRegistre02', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ValeurCalculTheoriqueCarnetRapport02', NumberType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnneePrevisionnelle::class,
        ]);
    }
}
