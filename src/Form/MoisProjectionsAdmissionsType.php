<?php

namespace App\Form;

use App\Entity\CommandeTrimestrielle;
use App\Entity\Groupe;
use App\Entity\MoisProjectionsAdmissions;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoisProjectionsAdmissionsType extends AbstractType
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
            ->add('Groupe', EntityType::class, [
                'class' => Groupe::class,
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
            ->add('CommandeTrimestrielle', EntityType::class, [
                'class' => CommandeTrimestrielle::class,
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
            ->add('MoisAdmissionCRENASAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('MoisAdmissionProjeteAnneePrecedent', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('MoisProjectionAnneePrevisionnelle', ChoiceType::class, [
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
            'data_class' => MoisProjectionsAdmissions::class,
        ]);
    }
}
