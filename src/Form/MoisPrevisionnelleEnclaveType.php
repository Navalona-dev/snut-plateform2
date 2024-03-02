<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\MoisPrevisionnelleEnclave;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoisPrevisionnelleEnclaveType extends AbstractType
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
            ->add('mois', ChoiceType::class, [
                'choices' => $mois,
                'placeholder' => 'Choisir mois',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.Nom', 'ASC');
                },
                'choice_label' => 'Nom',
                'multiple' => false,
                'required' => false,
                //'by_reference' => false,
                'placeholder' => 'Choisir groupe',
                'attr'=> ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-fill btn-green mx-auto']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MoisPrevisionnelleEnclave::class,
        ]);
    }
}
