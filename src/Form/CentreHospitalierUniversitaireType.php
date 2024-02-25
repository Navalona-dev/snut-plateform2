<?php

namespace App\Form;

use App\Entity\CentreHospitalierUniversitaire;
use App\Entity\District;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreHospitalierUniversitaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('District', EntityType::class, [
                'class' => District::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.Nom', 'ASC');
                },
                'choice_label' => 'Nom',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir district',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CentreHospitalierUniversitaire::class,
        ]);
    }
}
